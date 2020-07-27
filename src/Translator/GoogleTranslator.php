<?php
    /**
     * GoogleTranslator
     *
     * @copyright Copyright © 2020 <company_name>. All rights reserved.
     * @author    <user_email>
     */
    
    namespace firegore\AutoTranslationShopCSV\Translator;
    
    
    use Exception;
    use firegore\AutoTranslationShopCSV\Singleton;
    use GuzzleHttp\Client;
    use GuzzleHttp\Psr7\Response;
    use GuzzleHttp\RequestOptions;
    use Illuminate\Support\Collection;
    use Psr\Http\Message\ResponseInterface;
    
    class GoogleTranslator
    {
        use Singleton;
        
        const BASE_URL = 'https://translate.google.com/translate_a/single';
        /**
         * @var \GuzzleHttp\Client $guzzle_client
         */
        protected $guzzle_client;
        /**
         * @var \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Psr7\Response $last_response
         */
        protected $last_response;
        protected $max_tries = 20;
        protected $tries     = 0;
        protected $localFail = false;
        
        
        public function __construct ()
        {
            $this->setGuzzleClient(
                new Client(
                    [
                        "base_uri" => $this::BASE_URL,
                        "defaults" => [
                        ],
                    ]
                )
            );
        }
        
        /**
         * @param            $text
         * @param            $target
         * @param   string   $source
         *
         * @return string
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function translate ($text, $target, $source = Codes::IDENTIFY)
        {
            $this->tries = 0;
            $this->requestTranslation($text, $target, $source);
            
            return $this->getSentences();
        }
        
        /**
         * @param $source
         * @param $target
         * @param $text
         *
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function requestTranslation ($text, $target, $source = Codes::IDENTIFY)
        {
            if ($this->max_tries <= $this->tries)
                throw new Exception("Numero máximo de solicitudes de traducción", 0);
            $queryArray = [
                "client" => 'webapp',
                "dt"     => ['at', 'bd', 'ex', 'ld', 'md', 'qca', 'rw', 'rm', 'ss', 't'],
                "ie"     => 'UTF-8',
                "oe"     => 'UTF-8',
                "otf"    => 1,
                "ssel"   => 5,
                "tsel"   => 5,
                "kc"     => 9,
                'sl'     => $source,
                'tl'     => $target,
                'hl'     => $target,
                'tk'     => (new GoogleTokenGenerator())->generateToken($source, $target, $text),
                'q'      => $text,
            ];
            
            if (strlen($queryArray['q']) >= 5000)
                throw new Exception("Maximum number of characters exceeded: 5000");
            $queryUrl = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($queryArray));
            
            try {
                
                $this->setLastResponse(
                    $this->getGuzzleClient()
                         ->get(
                             'https://translate.google.com/translate_a/single',
                             $this->defaultRequestOptions($queryUrl)
                         )
                );
                $this->tries++;
                if ($this->getLastResponse()->getStatusCode() !== 200) {
                    if (!$this->isLocalFail()) $this->setLocalFail(true);
                    $this->getProxy(true);
                    $this->requestTranslation($source, $target, $text);
                }
                
            }
            catch (Exception $e) {
                $this->tries++;
                $this->getProxy(true);
                $this->requestTranslation($source, $target, $text);
            }
        }
        
        /**
         * @return \GuzzleHttp\Client
         */
        public function getGuzzleClient ()
        {
            return $this->guzzle_client;
        }
        
        /**
         * @param   \GuzzleHttp\Client   $guzzle_client
         *
         * @return GoogleTranslator
         */
        public function setGuzzleClient (Client $guzzle_client)
        {
            $this->guzzle_client = $guzzle_client;
            return $this;
        }
        
        public function getProxy ($renew = false)
        {
            static $proxyList;
            static $proxy;
            
            if (!$proxyList || !$proxyList->count()) {
                $regexp =
                    "/(?<ip>[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})\:?(?P<port>[0-9]{1,5})?\s*\-?(?P<country>[A-Z]{1,2})?\-?(?P<anonymity>[A-Z][!]?)?\s*?(\-)?(?P<type>[A-Z][!]?)?\s*?(?P<google>[\+|\-])/";
                preg_match_all($regexp, file_get_contents("https://raw.githubusercontent.com/clarketm/proxy-list/master/proxy-list.txt"), $matches, PREG_SET_ORDER);
                $proxyList = collect($matches)->map(
                    function ($item, $key) {
                        $new_collection = collect($item)->filter(
                            function ($item, $key) {
                                return !is_numeric($key);
                            }
                        );
                        $new_collection->put("type", !empty($new_collection->get("type")) ? $new_collection->get("type") : $new_collection->get("anonymity"));
                        return $new_collection->toArray();
                    }
                )->filter(
                    function ($item) {
                        return $item["google"] == "+";
                    }
                );
            }
            return (!$proxy || $renew) ? $proxy = $proxyList->pop() : $proxy;
        }
        
        /**
         * @param   array   $fields
         *
         * @return array
         */
        public function defaultRequestOptions ($fields = [])
        {
            $options = [
                RequestOptions::VERIFY => false,
                RequestOptions::QUERY  => $fields,
            ];
            if ($this->isLocalFail()) {
                $proxy                          = $this->getProxy();
                $options[RequestOptions::PROXY] = "http://$proxy[ip]:$proxy[port]";
            }
            return $options;
        }
        
        /**
         * @return \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Psr7\Response
         */
        public function getLastResponse ()
        {
            return $this->last_response;
        }
        
        /**
         * @param   \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Psr7\Response   $last_response
         *
         * @return GoogleTranslator
         */
        public function setLastResponse (Response $last_response)
        {
            $this->last_response = $last_response;
            return $this;
        }
        
        /**
         * @return \firegore\AutoTranslationShopCSV\Translator\Translation
         */
        public function getSentences ()
        {
            $response = $this->getLastResponse();
            $body     = $response->getBody()->getContents();
            $response->getBody()->seek(0);
            $decodedBody = \GuzzleHttp\json_decode($body, true);
            return new Translation($decodedBody);
            
        }
        
        /**
         * @return int
         */
        public function getMaxTries ()
        {
            return $this->max_tries;
        }
        
        /**
         * @param   int   $max_tries
         *
         * @return GoogleTranslator
         */
        public function setMaxTries (int $max_tries)
        {
            $this->max_tries = $max_tries;
            return $this;
        }
        
        /**
         * @return int
         */
        public function getTries ()
        {
            return $this->tries;
        }
        
        /**
         * @param   int   $tries
         *
         * @return GoogleTranslator
         */
        public function setTries (int $tries)
        {
            $this->tries = $tries;
            return $this;
        }
        
        /**
         * @return bool
         */
        protected function isLocalFail ()
        {
            return $this->localFail;
        }
        
        /**
         * @param   bool   $localFail
         *
         * @return GoogleTranslator
         */
        protected function setLocalFail (bool $localFail)
        {
            $this->localFail = $localFail;
            return $this;
        }
    }