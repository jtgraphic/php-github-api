<?php

class Github_API {
    private $_curl;
    private $_output;
    private $_credentials;
    private $_organization;
    private $_repo;
    private $_endpoint;
    private $_method;
    private $_response;

    public function __construct()
    {
        $this->_curl = curl_init();

        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, TRUE);
    }

    public function __destruct()
    {
        curl_close($this->_curl);
    }

    public static function factory()
    {
        return new static();
    }

    public function set_credentials($user, $password)
    {
        $this->_credentials = $user.':'.$password;

        curl_setopt($curl, CURLOPT_USERPWD, $this->_credentials);

        return $this;
    }

    public function set_password($password)
    {
        $this->_password = $password;

        return $this;
    }

    public function set_repo($repo)
    {
        $this->_repo = $repo;

        return $this;
    }

    public function set_organization($organization)
    {
        $this->_organization = $organization;

        return $this;
    }

    private function _set_comment_endpoint($issue_number)
    {
        $this->_endpoint = 'https://api.github.com/repos/'.$this->_organization.'/'.$this->_repo.'/issues/'.$issue_number.'/comments';
        curl_setopt($this->_curl, CURLOPT_URL, $this->_endpoint);
    }

    public function post_comment($issue_number, $body)
    {
        $this->_set_comment_endpoint($issue_number);
        $this->_method = 'POST';

        $post_fields = array(
            'body' => $body
        );

        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, json_encode($post_fields));

        return $this;
    }

    public function get_comments($issue_number)
    {
        $this->_set_comment_endpoint($issue_number);
        $this->_method = 'GET';

        return $this;
    }

    public function get_pull_requests($state = 'open')
    {
        $this->_endpoint = 'https://api.github.com/repos/'.$this->_organization.'/'.$this->_repo.'/pulls?state='.$state
        $this->_method = 'GET';

        curl_setopt($this->_curl, CURLOPT_URL, $this->_endpoint);

        return $this;
    }

    public function execute()
    {
        if ($this->_method == 'POST') {
            curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($this->_curl, CURLOPT_POST, TRUE);
        } else {
            curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array());
            curl_setopt($this->_curl, CURLOPT_POST, FALSE);
        }

        $this->_response = curl_exec($this->_curl);

        if (!$this->_response) {
            $this->_response = curl_error($this->_curl);
        }

        return $this;
   }

   public function return_as_json()
   {
       return $this->_response;
   }

   public function return_as_object()
   {
       return json_decode($this->_response);
   }

   public function return_as_array()
   {
       return json_decode($this->_response, TRUE);
   }
}


