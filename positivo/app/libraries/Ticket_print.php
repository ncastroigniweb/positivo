
<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  ==============================================================================
 *  Author    : Mian Saleem
 *  Email    : saleem@tecdiary.com
 *  For        : Stock Manager Advance
 *  Web        : http://tecdiary.com
 *  ==============================================================================
 */

require_once APPPATH . "/third_party/ticket/autoload.php";


use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class Ticket_print extends WindowsPrintConnector
{
	public function __construct()
    {

    }

     public function tickPrint($data)
    {
    	//$impresora = $data['printer'];
    	$impresora = "Generic";
		$connector = new WindowsPrintConnector($impresora);
		$printer = new Printer($connector);



		//echo 1;


		# Vamos a alinear al centro lo próximo que imprimamos
		$printer->setJustification(Printer::JUSTIFY_CENTER);

		/*
			Intentaremos cargar e imprimir
			el logo
		*/
		try{
			$logo = EscposImage::load("geek.png", false);
		    $printer->bitImage($logo);
		}catch(Exception $e){/*No hacemos nada si hay error*/}
		/*
			Ahora vamos a imprimir un encabezado
		*/
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		$printer->text("\n"."Factura No." .$data['inv']->id. "\n");
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		$printer->text("\n". strtoupper($data['inv']->biller). "\n");
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		$printer->text("Direccion: "
								.$data['biller']->address." "
								.$data['biller']->city." "
								.$data['biller']->state." "
								.$data['biller']->country
								. "\n");
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		$printer->text("Tel: " .$data['biller']->phone. "\n");


		$printer->text("Referencia: ".	$data['id_venta'] ."\n");
		$printer->text("Cliente: "	. $data['inv']->customer.	 "\n");
		$printer->text(date("Y-m-d H:i:s") . "\n");



		#La fecha también
		date_default_timezone_set("America/Bogota");
		$printer->text("-----------------------------" . "\n");
		$printer->setJustification(Printer::JUSTIFY_LEFT);
		$printer->text("CANT  DESCRIPCION    P.U   SUB.\n");
		$printer->text("-----------------------------"."\n");
		/*
			Ahora vamos a imprimir los
			productos
		*/
			/*Alinear a la izquierda para la cantidad y el nombre*/

			foreach ($data['rows'] as $row) {
				$printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(
                	round($row->quantity)		." ".
                	$row->product_name			." $".
                	round($row->net_unit_price) ." $".
                	round($row->subtotal).		"\n");

            }

		/*
			Terminamos de imprimir
			los productos, ahora va el total
		*/
		$printer->text("-----------------------------"."\n");

		$printer->setJustification(Printer::JUSTIFY_RIGHT);
		$printer->text("SUBTOTAL: $".round($data['inv']->total)."\n");
		$printer->setJustification(Printer::JUSTIFY_RIGHT);
		$printer->text("IMPUESTOS: $".round($data['inv']->total_tax)."\n");
		$printer->setJustification(Printer::JUSTIFY_RIGHT);
		$printer->text("TOTAL: $".round($data['inv']->grand_total)."\n");



		/*
			Podemos poner también un pie de página
		*/
		$printer->setJustification(Printer::JUSTIFY_CENTER);
		$printer->text($data['biller']->invoice_footer."\n");



		/*Alimentamos el papel 3 veces*/
		$printer->feed(3);

		/*
			Cortamos el papel. Si nuestra impresora
			no tiene soporte para ello, no generará
			ningún error
		*/
		$printer->cut();

		/*
			Por medio de la impresora mandamos un pulso.
			Esto es útil cuando la tenemos conectada
			por ejemplo a un cajón
		*/
		$printer->pulse();

		/*
			Para imprimir realmente, tenemos que "cerrar"
			la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
		*/
		$printer->close();

	}
}

