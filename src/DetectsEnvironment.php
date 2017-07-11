<?php

namespace AfricasTalking;

trait DetectsEnvironment
{
    /**
     * Base API endpoint.
     *
     * @var string
     */
    protected $apiEndpoint = 'https://api.africastalking.com/version1';

    /**
     * Voice API resource endpoint.
     *
     * @var string
     */
    protected $voiceEndpoint = 'https://voice.africastalking.com';

    /**
     * Payments API resource endpoint.
     *
     * @var string
     */
    protected $paymentsEndpoint = 'https://payments.africastalking.com';

   /**
    * undocumented function.
    *
    * @author
    **/
   protected function detect(bool $sandbox)
   {
       if ($sandbox) {
           $this->apiEndpoint = 'https://api.sandbox.africastalking.com/version1';
           $this->voiceEndpoint = 'https://voice.sandbox.africastalking.com';
           $this->paymentsEndpoint = 'https://voice.sandbox.africastalking.com';
       }
   }
}
