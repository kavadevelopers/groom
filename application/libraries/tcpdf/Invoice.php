<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/Tcpdf.php';

class Invoice extends TCPDF
{
	public $data;

    function set($data = false)
    {
        $this->data = $data;
    }

    public function Footer()
    {
    	// $this->SetY(-10);
    	// $this->SetFont('helvetica', 'I', 8);
     //    $this->Cell(0, 10, "This is computer generated invoice doesn't required signature", 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function Header()
    {
    	$this->Image($this->data['image'], 10, 10, 190, 35, $this->data['extention'], '', '', false, 300, '', false, false, 0);
    }
}
