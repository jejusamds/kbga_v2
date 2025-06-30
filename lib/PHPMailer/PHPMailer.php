<?php
namespace PHPMailer\PHPMailer;

class PHPMailer {
    public $Host = 'localhost';
    public $Port = 25;
    public $Username = '';
    public $Password = '';
    public $SMTPSecure = '';
    public $SMTPAuth = false;
    public $From = '';
    public $FromName = '';
    public $Subject = '';
    public $Body = '';
    protected $to = [];
    protected $cc = [];
    protected $bcc = [];
    protected $isHTML = false;
    protected $isSMTP = false;

    public function isSMTP() { $this->isSMTP = true; }
    public function setFrom($address, $name = '') { $this->From = $address; $this->FromName = $name; }
    public function addAddress($address, $name = '') { $this->to[] = ['address'=>$address,'name'=>$name]; }
    public function addCC($address, $name = '') { $this->cc[] = ['address'=>$address,'name'=>$name]; }
    public function addBCC($address, $name = '') { $this->bcc[] = ['address'=>$address,'name'=>$name]; }
    public function isHTML($bool = true) { $this->isHTML = $bool; }

    protected function expect($fp, $code) {
        $line = '';
        while ($line === '' || $line[3] != ' ') {
            $line = fgets($fp, 515);
        }
        if (substr($line,0,3) != $code) {
            throw new Exception('SMTP error: '.$line);
        }
    }

    public function send() {
        if (!$this->isSMTP) {
            $headers = "From: {$this->FromName} <{$this->From}>\r\n";
            if ($this->isHTML) $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            $to = array_map(function($x){return $x['address'];}, $this->to);
            if ($this->cc) $headers .= 'Cc: '.implode(',', array_map(function($x){return $x["address"];}, $this->cc))."\r\n";
            if ($this->bcc) $headers .= 'Bcc: '.implode(',', array_map(function($x){return $x["address"];}, $this->bcc))."\r\n";
            return mail(implode(',', $to), $this->Subject, $this->Body, $headers);
        }
        $host = ($this->SMTPSecure=='ssl'?'ssl://':'').$this->Host;
        $fp = fsockopen($host, $this->Port, $errno, $errstr, 10);
        if (!$fp) return false;
        $this->expect($fp, 220);
        $local = gethostname();
        fwrite($fp, "EHLO {$local}\r\n");
        $this->expect($fp, 250);
        if ($this->SMTPSecure=='tls') {
            fwrite($fp, "STARTTLS\r\n");
            $this->expect($fp, 220);
            stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            fwrite($fp, "EHLO {$local}\r\n");
            $this->expect($fp, 250);
        }
        if ($this->SMTPAuth) {
            fwrite($fp, "AUTH LOGIN\r\n");
            $this->expect($fp, 334);
            fwrite($fp, base64_encode($this->Username)."\r\n");
            $this->expect($fp, 334);
            fwrite($fp, base64_encode($this->Password)."\r\n");
            $this->expect($fp, 235);
        }
        fwrite($fp, "MAIL FROM:<{$this->From}>\r\n");
        $this->expect($fp, 250);
        $all = array_merge($this->to,$this->cc,$this->bcc);
        foreach ($all as $addr) {
            fwrite($fp, "RCPT TO:<{$addr['address']}>\r\n");
            $this->expect($fp, 250);
        }
        fwrite($fp, "DATA\r\n");
        $this->expect($fp, 354);
        $headers = "From: {$this->FromName} <{$this->From}>\r\n";
        $toHdr = implode(',', array_map(function($x){return $x['address'];}, $this->to));
        $headers .= "To: {$toHdr}\r\n";
        if ($this->cc) $headers .= 'Cc: '.implode(',', array_map(function($x){return $x["address"];}, $this->cc))."\r\n";
        if ($this->bcc) $headers .= 'Bcc: '.implode(',', array_map(function($x){return $x["address"];}, $this->bcc))."\r\n";
        $headers .= "Subject: {$this->Subject}\r\n";
        if ($this->isHTML) $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        fwrite($fp, $headers."\r\n".$this->Body."\r\n.\r\n");
        $this->expect($fp, 250);
        fwrite($fp, "QUIT\r\n");
        fclose($fp);
        return true;
    }
}