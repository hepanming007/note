<?php

namespace wei;

/**
 * IP ����λ�ò�ѯ��
 *      �� Joel Huang ��΢�����޸�Ϊ php5�﷨��PSR-2 ������
 *      ������ UTF-8 ����� country, area
 *
 * @author ���Ң
 * @version 1.5
 * @copyright 2005 CoolCode.CN
 */
class IpLocation
{
    /**
     * qqwry.dat�ļ�ָ��
     *
     * @var resource
     */
    private $fp;

    /**
     * ��һ��IP��¼��ƫ�Ƶ�ַ
     *
     * @var int
     */
    private $firstIp;

    /**
     * ���һ��IP��¼��ƫ�Ƶ�ַ
     *
     * @var int
     */
    private $lastIp;

    /**
     * IP��¼�����������������汾��Ϣ��¼��
     *
     * @var int
     */
    private $totalIp;

    /**
     * ���캯������ qqwry.dat �ļ�����ʼ�����е���Ϣ
     *
     * @param string $filename
     * @return IpLocation
     */
    public function __construct($filename = 'qqwry.dat')
    {
        $this->fp = 0;
        if (($this->fp = fopen(__DIR__ . '/' . $filename, 'rb')) !== false) {
            $this->firstIp = $this->getLong();
            $this->lastIp = $this->getLong();
            $this->totalIp = ($this->lastIp - $this->firstIp) / 7;
        }
    }

    /**
     * ����������������ҳ��ִ�н������Զ��رմ򿪵��ļ���
     */
    public function __destruct()
    {
        if ($this->fp) {
            fclose($this->fp);
        }
        $this->fp = 0;
    }

    /**
     * ���ض�ȡ�ĳ�������
     *
     * @return int
     */
    private function getLong()
    {
        //����ȡ��little-endian�����4���ֽ�ת��Ϊ��������
        $result = unpack('Vlong', fread($this->fp, 4));
        return $result['long'];
    }

    /**
     * ���ض�ȡ��3���ֽڵĳ�������
     *
     * @return int
     */
    private function getLong3()
    {
        //����ȡ��little-endian�����3���ֽ�ת��Ϊ��������
        $result = unpack('Vlong', fread($this->fp, 3) . chr(0));
        return $result['long'];
    }

    /**
     * ����ѹ����ɽ��бȽϵ�IP��ַ
     *
     * @param string $ip
     * @return string
     */
    private function packIp($ip)
    {
        // ��IP��ַת��Ϊ���������������PHP5�У�IP��ַ�����򷵻�False��
        // ��ʱintval��Flaseת��Ϊ����-1��֮��ѹ����big-endian������ַ���
        return pack('N', intval(ip2long($ip)));
    }

    /**
     * ���ض�ȡ���ַ���
     *
     * @param string $data
     * @return string
     */
    private function getString($data = "")
    {
        $char = fread($this->fp, 1);
        while (ord($char) > 0) { // �ַ�������C��ʽ���棬��\0����
            $data .= $char; // ����ȡ���ַ����ӵ������ַ���֮��
            $char = fread($this->fp, 1);
        }
        return $data;
    }

    /**
     * ���ص�����Ϣ
     *
     * @return string
     */
    private function getArea()
    {
        $byte = fread($this->fp, 1); // ��־�ֽ�
        switch (ord($byte)) {
            case 0: // û��������Ϣ
                $area = "";
                break;
            case 1:
            case 2: // ��־�ֽ�Ϊ1��2����ʾ������Ϣ���ض���
                fseek($this->fp, $this->getLong3());
                $area = $this->getString();
                break;
            default: // ���򣬱�ʾ������Ϣû�б��ض���
                $area = $this->getString($byte);
                break;
        }
        return $area;
    }

    /**
     * �������� IP ��ַ�������������ڵ�����Ϣ
     *
     * @param string $ip
     * @return array
     */
    public function getLocation($ip)
    {

        if (!$this->fp) {
            return null;
        } // ��������ļ�û�б���ȷ�򿪣���ֱ�ӷ��ؿ�
        $location['ip'] = gethostbyname($ip); // �����������ת��ΪIP��ַ

        $ip = $this->packIp($location['ip']); // �������IP��ַת��Ϊ�ɱȽϵ�IP��ַ
        // ���Ϸ���IP��ַ�ᱻת��Ϊ255.255.255.255
        // �Է�����
        $l = 0; // �������±߽�
        $u = $this->totalIp; // �������ϱ߽�
        $findIp = $this->lastIp; // ���û���ҵ��ͷ������һ��IP��¼��qqwry.dat�İ汾��Ϣ��
        while ($l <= $u) { // ���ϱ߽�С���±߽�ʱ������ʧ��
            $i = floor(($l + $u) / 2); // ��������м��¼
            fseek($this->fp, $this->firstIp + $i * 7);
            $beginIp = strrev(fread($this->fp, 4)); // ��ȡ�м��¼�Ŀ�ʼIP��ַ
            // strrev����������������ǽ�little-endian��ѹ��IP��ַת��Ϊbig-endian�ĸ�ʽ
            // �Ա����ڱȽϣ�������ͬ��
            if ($ip < $beginIp) { // �û���IPС���м��¼�Ŀ�ʼIP��ַʱ
                $u = $i - 1; // ���������ϱ߽��޸�Ϊ�м��¼��һ
            } else {
                fseek($this->fp, $this->getLong3());
                $endIp = strrev(fread($this->fp, 4)); // ��ȡ�м��¼�Ľ���IP��ַ
                if ($ip > $endIp) { // �û���IP�����м��¼�Ľ���IP��ַʱ
                    $l = $i + 1; // ���������±߽��޸�Ϊ�м��¼��һ
                } else { // �û���IP���м��¼��IP��Χ��ʱ
                    $findIp = $this->firstIp + $i * 7;
                    break; // ���ʾ�ҵ�������˳�ѭ��
                }
            }
        }

        //��ȡ���ҵ���IP����λ����Ϣ
        fseek($this->fp, $findIp);
        $location['begin_ip'] = long2ip($this->getLong()); // �û�IP���ڷ�Χ�Ŀ�ʼ��ַ
        $offset = $this->getLong3();
        fseek($this->fp, $offset);
        $location['end_ip'] = long2ip($this->getLong()); // �û�IP���ڷ�Χ�Ľ�����ַ
        $byte = fread($this->fp, 1); // ��־�ֽ�
        switch (ord($byte)) {
            case 1: // ��־�ֽ�Ϊ1����ʾ���Һ�������Ϣ����ͬʱ�ض���
                $countryOffset = $this->getLong3(); // �ض����ַ
                fseek($this->fp, $countryOffset);
                $byte = fread($this->fp, 1); // ��־�ֽ�
                switch (ord($byte)) {
                    case 2: // ��־�ֽ�Ϊ2����ʾ������Ϣ�ֱ��ض���
                        fseek($this->fp, $this->getLong3());
                        $location['country'] = $this->getString();
                        fseek($this->fp, $countryOffset + 4);
                        $location['area'] = $this->getArea();
                        break;
                    default: // ���򣬱�ʾ������Ϣû�б��ض���
                        $location['country'] = $this->getString($byte);
                        $location['area'] = $this->getArea();
                        break;
                }
                break;
            case 2: // ��־�ֽ�Ϊ2����ʾ������Ϣ���ض���
                fseek($this->fp, $this->getLong3());
                $location['country'] = $this->getString();
                fseek($this->fp, $offset + 8);
                $location['area'] = $this->getArea();
                break;
            default: // ���򣬱�ʾ������Ϣû�б��ض���
                $location['country'] = $this->getString($byte);
                $location['area'] = $this->getArea();
                break;
        }

        if ($location['country'] == ' CZ88.NET') { // CZ88.NET��ʾû����Ч��Ϣ
            $location['country'] = 'δ֪';
        }
        if ($location['area'] == ' CZ88.NET') {
            $location['area'] = '';
        }

        return $location;
    }
}
