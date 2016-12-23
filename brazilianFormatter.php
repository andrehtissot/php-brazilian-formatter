<?php
class BrazilianFormatter {
    static function formatAndSplitPhoneAsPossible($originDDD, $originNumber) {
        $formattedPhoneNumber = self::formatPhoneAsPossible($originDDD, $originNumber);
        if(preg_match('/^(\(\d\d\)\d{4}-)(\d{4}) ?\/ *(\d{4})$/', $formattedPhoneNumber, $matches))
            return array($matches[1].$matches[2],$matches[1].$matches[3]);
        if(preg_match('/^(\(\d\d\)\d{4}-\d{4}) *; *(\d\d)-(\d{4})(\d{4})$/', $formattedPhoneNumber, $matches))
            return array($matches[1],'('.$matches[2].')'.$matches[3].'-'.$matches[4]);
        return array($formattedPhoneNumber,null);
    }

    static function formatPhoneAsPossible($originDDD, $originNumber) {
        if(trim(''.$originNumber) === '') { return null; }
        if(trim(''.$originDDD) === '') $ddd = '';
        else $ddd = '('.trim(''.$originDDD).')';
        $number = trim(''.$originNumber);
        if(preg_match('/\D/', $number)){
            if(trim($ddd) !== '' && strpos($number, trim($ddd)) === 0)
                $number = trim(substr($number, strlen(trim($ddd))));
            if(strpos($number, '*') === 0)
                $number = substr($number, 1);
            if(strrpos($number, '.') === strlen($number)-1)
                $number = substr($number, 0, -1);
            if(preg_match('/^(\d{3}\d?\d?)\D(\d{4})$/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2];
            if(strlen($originDDD) == 2 && preg_match('/^0'.$originDDD.' (\d{4}) (\d{4})$/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2];
            if(preg_match('/^(\d{3}\d?\d?)\D?(\d{4})(\D+)([\D\d]+)/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2] . ' ' . trim($matches[3].$matches[4]);
            if(preg_match('/^(\d{3}\d?\d?)\D*(\d{4})$/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2];
            if(preg_match('/^(\d\d\d?\d?)(\d)\D*(\d{3})(\D+)$/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2] . ' ' . trim($matches[3]);
            if(preg_match('/^(\d\d\d?\d?)(\d)\D*(\d{3})$/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2].$matches[3];
            if(preg_match('/^(\d{3}\d?\d?)\D*(\d\d) (\d\d)$/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2] . $matches[3];
            if(preg_match('/^\d\d-(\d{4})-(\d{4})$/', $number, $matches) && $originDDD === substr($number,0,2))
                return $ddd . $matches[1] . '-' . $matches[2];
            if(preg_match('/^\d-\d{4}-\d{4}$/', $number))
                return $ddd . substr($number, 0, 1) . substr($number, 2, 4) . '-' . substr($number, 7);
            if(preg_match('/^(\d{4})-(\d)(\d{4})$/', $number, $matches))
                return $ddd . $matches[1] . $matches[2] . '-' . $matches[3];
            if(preg_match('/^(\d{4})-(\d)(\d{4})(\D+)([\D\d]+)$/', $number, $matches))
                return $ddd . $matches[1] . $matches[2] . '-' . $matches[3] . $matches[4] . $matches[5];
            if(preg_match('/^RAMAL \d+$/', $number, $matches))
                return $ddd . $number;
            if(preg_match('/^(\d{4})\D(\d)(\d{4})$/', $number, $matches))
                return $ddd . $matches[1] . $matches[2] . '-' . $matches[3];
            if(preg_match('/^(\d{2})(\d{2})\D(\d{2})$/', $number, $matches))
                return $ddd . $matches[1] . '-' . $matches[2] . $matches[3];
            if(preg_match('/^(\d{3})\D(\d{1})(\d{4})$/', $number, $matches))
                return $ddd . $matches[1] . $matches[2] . '-' . $matches[3];
        } else {
            if(strlen($number) < 5)
                return $ddd . $number;
            if(strlen($number) === 8)
                return $ddd . substr($number, 0, 4) . '-' . substr($number, 4);
            if(strlen($number) === 9)
                return $ddd . substr($number, 0, 5) . '-' . substr($number, 5);
            if(strlen($number) === 7)
                return $ddd . substr($number, 0, 3) . '-' . substr($number, 3);
            if(strlen($number) === 12)
                return $ddd . substr($number, 0, 4) . '-' . substr($number, 4, 4) . ' ' . substr($number, 8, 4);
            if(strlen($number) === 10 && substr($number,0,2) === $originDDD)
                return $ddd . substr($number, 2, 4) . '-' . substr($number, 6);
        }
        return $ddd . $number;
    }

    static function formatCPF($cpf) {
        $cpf = trim(''.$cpf);
        if($cpf === '') { return null; }
        if(strlen($cpf) < 4) { return $cpf; }
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
    }

    static function formatCEP($cep) {
        if(trim("$cep") === '') { return null; }
        if(trim("$cep") === '0') { return null; }
        $cep = trim("$cep");
        if(preg_match('/^\d{5}-\d{3}$/', $cep)) { return $cep; }
        if(preg_match('/^\d{5}-\d{2}$/', $cep)) { return "0$cep"; }
        if(strlen($cep) === 8)
            return substr($cep, 0, 5) . '-' . substr($cep, 5);
        if(strlen($cep) === 7)
            return '0' . substr($cep, 0, 4) . '-' . substr($cep, 4);
        if(strlen($cep) === 6)
            return '00' . substr($cep, 0, 3) . '-' . substr($cep, 3);
        return $cep;
    }
}
