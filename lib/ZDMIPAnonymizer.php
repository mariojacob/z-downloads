<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

/**
 * Klasse um IPv4 und IPv6 Adressen zu anonymisieren<br>
 * <br><br>
 * Examples:<br>
 * <br>
 * $ipAnonymizer = new IPAnonymizer();<br>
 * <br>
 * var_dump($ipAnonymizer->anonymize('127.0.0.1'));<br>
 * returns 127.0.0.0<br>
 * <br>
 * var_dump($ipAnonymizer->anonymize('192.168.178.123'));<br>
 * returns 192.168.178.0<br>
 * <br>
 * var_dump($ipAnonymizer->anonymize('8.8.8.8'));<br>
 * returns 8.8.8.0<br>
 * <br>
 * var_dump($ipAnonymizer->anonymize('::1'));<br>
 * returns ::<br>
 * <br>
 * var_dump($ipAnonymizer->anonymize('::127.0.0.1'));<br>
 * returns ::<br>
 * <br>
 * var_dump($ipAnonymizer->anonymize('2a03:2880:2110:df07:face:b00c::1'));<br>
 * returns 2a03:2880:2110:df07::<br>
 * <br>
 * var_dump($ipAnonymizer->anonymize('2610:28:3090:3001:dead:beef:cafe:fed3'));<br>
 * returns 2610:28:3090:3001::<br>
 * <br>
 * Verwende eine benutzerdefinierte Mask:<br>
 * $ipAnonymizer->ipv4NetMask = "255.255.0.0";<br>
 * $ipAnonymizer->ipv4NetMask = "255.255.0.0";<br>
 * // returns 192.168.0.0<br>
 * <br>
 * You can also use this class statically:<br>
 * var_dump(IpAnonymizer::anonymizeIp('192.168.178.123'));<br>
 * returns 192.168.178.0<br>
 * <br>
 * var_dump(IpAnonymizer::anonymizeIp('2610:28:3090:3001:dead:beef:cafe:fed3'));<br>
 * returns 2610:28:3090:3001::
 */
class ZDMIPAnonymizer {
    /**
     * @var string IPv4 netmask for anonymizing the IPv4 address.
     */
    public $ipv4NetMask = "255.255.255.0";
    /**
     * @var string IPv6 netmask for anonymizing the IPv6 address.
     */
    public $ipv6NetMask = "ffff:ffff:ffff:ffff:0000:0000:0000:0000";
    /**
     * Anonymisiert eine IPv4- oder IPv6-Adresse
     *
     * @param string $address IP-Adresse, die anonymisiert werden muss
     * @return string Die anonymisierte IP-Adresse. Gibt eine leere Zeichenfolge zurück, wenn die IP-Adresse ungültig ist
     */
    public static function anonymizeIp($address) {
        $anonymizer = new IPAnonymizer();
        return $anonymizer->anonymize($address);
    }
    /**
     * Anonymisiert eine IPv4- oder IPv6-Adresse
     *
     * @param string $address IP-Adresse, die anonymisiert werden muss
     * @return string Die anonymisierte IP-Adresse. Gibt eine leere Zeichenfolge zurück, wenn die IP-Adresse ungültig ist
     */
    public function anonymize($address) {
        $packedAddress = inet_pton($address);
        if (strlen($packedAddress) == 4) {
            return $this->anonymizeIPv4($address);
        } elseif (strlen($packedAddress) == 16) {
            return $this->anonymizeIPv6($address);
        } else {
            return "";
        }
    }
    /**
     * Anonymisiert eine IPv4-Adresse
     * @param string $address IPv4-Adresse
     * @return string Anonymisierte IP-Adresse
     */
    public function anonymizeIPv4($address) {
        return inet_ntop(inet_pton($address) & inet_pton($this->ipv4NetMask));
    }
    /**
     * Anonymisiert eine IPv6-Adresse
     * @param string $address IPv6-Adresse
     * @return string Anonymisierte IP-Adresse
     */
    public function anonymizeIPv6($address) {
        return inet_ntop(inet_pton($address) & inet_pton($this->ipv6NetMask));
    }
}