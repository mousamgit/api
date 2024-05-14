<?php
require('./fpdf184/fpdf.php');
$sku = $_GET["sku"];
$skufound = FALSE;
$xml=simplexml_load_file("../xml/sd_stones.xml") or die("Error: Cannot create object");

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

class PDF extends FPDF
{
protected $B = 0;
protected $I = 0;
protected $U = 0;
protected $HREF = '';




// Page header
function Header()
{
    // Logo


}

// // Page footer
// function Footer()
// {
//     // Position at 1.5 cm from bottom
//     $this->SetY(-15);
//     // Arial italic 8
//     $this->SetFont('Arial','I',8);
//     // Page number
//     $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
// }
function WriteHTML($html)
{
	// HTML parser
	$html = str_replace("\n",' ',$html);
	$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			// Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			else
				$this->Write(5,$e);
		}
		else
		{
			// Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				// Extract attributes
				$a2 = explode(' ',$e);
				$tag = strtoupper(array_shift($a2));
				$attr = array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])] = $a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	// Opening tag
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,true);
	if($tag=='A')
		$this->HREF = $attr['HREF'];
	if($tag=='BR')
		$this->Ln(5);
}

function CloseTag($tag)
{
	// Closing tag
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF = '';
}

function SetStyle($tag, $enable)
{
	// Modify style and select corresponding font
	$this->$tag += ($enable ? 1 : -1);
	$style = '';
	foreach(array('B', 'I', 'U') as $s)
	{
		if($this->$s>0)
			$style .= $s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	// Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}
function ChapterBody($file)
{
    // Read text file
    $txt = file_get_contents($file);
    // Times 12
    $this->SetFont('Arial','',8);
    // Output justified text
    $this->MultiCell(0,3,$txt,0,'C');
    // Line break
    $this->Ln();

}
function PrintChapter($num, $title, $file)
{
    $this->AddPage();
    $this->ChapterTitle($num,$title);
    $this->ChapterBody($file);
}
}

$html = '<p>This information has been generated against the inserted number from historical records held by Argyle Diamonds Limited. It does not constitute a representation as to origin or authenticity of the referenced diamond</p>';
$pktxt1 = 'Tracing its origins to the rugged landscapes of the East Kimberley region of Western Australia, every pink diamond from the Argyle mine is an extraordinary one-of-a-kind masterpiece in its own right.';
$pktxt2 = 'DISCLAIMER: THIS DOCUMENT IS NOT A VALUATION. IT DESCRIBES THE IDENTIFYING CHARACTERISTICS OF THE A DIAMOND UTILISING THE GRADING TECHNIQUES AND EQUIPMENT AVAILABLE TO PINK KIMBERLEY AT THE TIME OF ITS ASSESSMENT.';
$sdtxt1 = 'Each sapphire began its amazing journey millions of years ago, crystallising deep within the earth, transported to the surface by volcanoes and deposited on the sapphire fields of inland Eastern Australia. This gemstone \'s untapped potential is realised through the processes of precise cutting and excellent polishing. Subject to our rigorous assessment, all Sapphire Dreams sapphires are natural, ethically sourced and of Australian origin.';
$sdtxt2 = 'DISCLAIMER: THIS DOCUMENT IS NOT A VALUATION. IT DESCRIBES THE IDENTIFYING CHARACTERISTICS OF THE SAPPHIRE UTILISING THE GRADING TECHNIQUES AND EQUIPMENT AVAILABLE TO SAPPHIRE DREAMS AT THE TIME OF ITS ASSESSMENT.';

foreach($xml->children() as $product) {
    if($sku == $product->SKU){
        $brand=substr($sku,0,2);
        if($product->Stone_Specifications == ''){
            $idno = substr($sku, -5);
            $colour = $product->Stone_Colour;
            $shape = $product->Stone_Shape;
            $weight = $product->Carat_Weight;
            $size = $product->Measurement;
        }
        else{
            $idno = get_string_between($product->Stone_Specifications, 'ID No.: ', '<br>');
            $colour = get_string_between($product->Stone_Specifications, 'Colour: ', '<br>');
            $shape = get_string_between($product->Stone_Specifications, 'Shape: ', '<br>');
            $weight = get_string_between($product->Stone_Specifications, 'Weight: ', '<br>');
            $size = get_string_between($product->Stone_Specifications, 'Size: ', '<br>');
        }
            $pdf = new PDF('L','mm','A5');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial','',12);
            if(($product->Product_Type == 'Loose diamonds') || ($brand == 'PK')){
                $pdf->Image('pk-logo.jpg',75,15,55,'C');
                $pdf->SetTextColor(226,4,107);
            }
            if(($product->Product_Type == 'Loose sapphires') || ($brand == 'SD')){
                $pdf->Image('sd-logo.jpg',80,15,45,'C');
            }
            $pdf->Image('watermark.png',45,20,130,'C');
            // Title
            $pdf->Ln(35);
            
            $pdf->Cell(190,5,'GEM AUTHENTICITY CERTIFICATE',0,1,'C');
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(65);
            $pdf->Cell(30,5,'ID No.:',0,0,'L');
            $pdf->Cell(30,5,$idno ,0,0,'L');
            $pdf->Ln();
            $pdf->Cell(65);
            $pdf->Cell(30,5,'Colour:',0,0,'L');
            $pdf->Cell(30,5, $colour ,0,0,'L');
            $pdf->Ln();
            $pdf->Cell(65);
            $pdf->Cell(30,5,'Shape:',0,0,'L');
            $pdf->Cell(30,5, $shape ,0,0,'L');
            $pdf->Ln();
            $pdf->Cell(65);
            $pdf->Cell(30,5,'Weight:',0,0,'L');
            $pdf->Cell(30,5, $weight ,0,0,'L');
            $pdf->Ln();
            $pdf->Cell(65);
            $pdf->Cell(30,5,'Size:',0,0,'L');
            $pdf->Cell(30,5, $size ,0,0,'L');
            $pdf->Ln();
            $pdf->Cell(65);
            $pdf->Cell(30,5,'Origin:',0,0,'L');
            $pdf->Cell(30,5,'AUSTRALIA',0,0,'L');
            $pdf->Ln(10);
            if(($product->Product_Type == 'Loose sapphires') || ($brand == 'SD')){
                $pdf->Cell(180,5,'Natural Australian Sapphire',0,0,'C');
            }

            $pdf->Ln(10);
            $pdf->SetFont('Arial','',8);
            if(($product->Product_Type == 'Loose diamonds') || ($brand == 'PK')){
                $pdf->MultiCell(0,4,$pktxt1,0,'C');
                $pdf->Ln();
                $pdf->MultiCell(0,4,$pktxt2,0,'C');
            }
           
            if(($product->Product_Type == 'Loose sapphires') || ($brand == 'SD')){
                $pdf->MultiCell(0,4,$sdtxt1,0,'C');
                $pdf->Ln();
                $pdf->MultiCell(0,4,$sdtxt2,0,'C');
            }


            // $pdf->ChapterBody('sd1.txt');
            // $pdf->ChapterBody('sd2.txt');
            // $pdf->ChapterBody('Each sapphire began its amazing journey millions of years ago, crystallising deep within the earth, transported to the surface by volcanoes and deposited on the sapphire fields of inland Eastern Australia. This gemstone's untapped potential is realised through the processes of precise cutting and excellent polishing. Subject to our rigorous assessment, all Sapphire Dreams sapphires are natural, ethically sourced and of Australian origin.');
            // $pdf->Cell(30,5,'Each sapphire began its amazing journey millions of years ago, crystallising deep within the earth, transported to the surface by volcanoes and deposited on the sapphire fields of inland Eastern Australia. This gemstone\'s untapped potential is realised through the processes of precise cutting and excellent polishing. Subject to our rigorous assessment, all Sapphire Dreams sapphires are natural, ethically sourced and of Australian origin.',0,0,'C');
            // $pdf->Ln(10);
            // $pdf->ChapterBody('DISCLAIMER: THIS DOCUMENT IS NOT A VALUATION. IT DESCRIBES THE IDENTIFYING CHARACTERISTICS OF THE SAPPHIRE UTILISING THE GRADING TECHNIQUES AND EQUIPMENT AVAILABLE TO SAPPHIRE DREAMS AT THE TIME OF ITS ASSESSMENT.');
            // $pdf->Cell(30,5,'DISCLAIMER: THIS DOCUMENT IS NOT A VALUATION. IT DESCRIBES THE IDENTIFYING CHARACTERISTICS OF THE SAPPHIRE UTILISING THE GRADING TECHNIQUES AND EQUIPMENT AVAILABLE TO SAPPHIRE DREAMS AT THE TIME OF ITS ASSESSMENT.',0,0,'C');
      
            $pdf->Output();



        // Instanciation of inherited class
// $pdf = new PDF();
// $pdf->AliasNbPages();
// $pdf->AddPage();
// $pdf->SetFont('Times','',12);
// for($i=1;$i<=40;$i++)
//     $pdf->Cell(0,10,'Printing line number '.$i,0,1);
// $pdf->Output();

        $skufound = TRUE;
    }
  }
if($skufound == FALSE){
    echo "product not found";
}  

?>
