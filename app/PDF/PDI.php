<?php
namespace App\PDF;

require_once(__DIR__ . '/../../vendor/setasign/fpdi/src/Fpdi.php');

use setasign\Fpdi\Fpdi;

class PDI extends Fpdi
{
    public function __construct()
    {
        parent::__construct();
//
//        // Load Regular Font
//        $this->AddFont('ChakraPetch-Regular', '', 'ChakraPetch-Regular.php');
//
//        // Load Bold Font
//        $this->AddFont('ChakraPetch-Bold', '', 'ChakraPetch-Bold.php');
    }

    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                // Text
                if ($this->HREF) {
                    $this->PutLink($this->HREF, $e);
                } else {
                    $this->Write(5, $e);
                }
            } else {
                // Tag
                if ($e[0] == '/') {
                    $this->CloseTag(strtoupper(substr($e, 1)));
                } else {
                    // Extract attributes
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3)) {
                            $attr[strtoupper($a3[1])] = $a3[2];
                        }
                    }

                    // Handle different tags
                    switch ($tag) {
                        case 'B':
                        case 'I':
                        case 'U':
                            $this->OpenTag($tag, $attr);
                            break;
                        case 'A':
                            $this->HREF = $attr['HREF'];
                            break;
                        case 'BR':
                            $this->Ln(5);
                            break;
                        case 'P':
                            // Add a new line before the paragraph
                            $this->Ln(5);
                            break;
                    }
                }
            }
        }
        // Add a new line after the HTML content
        $this->Ln(10);
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
        if($tag=='P')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
        if($tag=='BR')
            $this->Ln(1);
        if($tag=='P')
            $this->Ln(1);
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s) {
            if ($this->$s > 0)
                $style .= $s;
        }

        // Set the font using the selected style
        if ($style == 'B') {
            $this->SetFont('ChakraPetch-Bold', '');
        } else {
            $this->SetFont('ChakraPetch-Regular', '');
        }
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

    function DoubleBorderCell($w, $h, $txt, $border=0, $ln=0, $align='', $fill=false) {
        // Calculate x and y position
        $x = $this->GetX();
        $y = $this->GetY();

        // Draw outer border
        $this->SetLineWidth(0.3);
        $this->Rect($x, $y, $w, $h, $border);

        // Draw inner border
        $this->SetLineWidth(0.3);
        $this->Rect($x + 0.3, $y + 0.3, $w - 0.6, $h - 0.6, $border);

        // Output the text
        $this->MultiCell($w, 5, $txt, 0, $align, $fill);

        // Move to the next line
        $this->SetXY($x + $w, $y);
    }

    function MultiCellWithHeight($w, $h, $txt, $border=0, $align='J', $fill=false) {
        // Estimate the height based on the text and width
        $lineHeight = 5; // Adjust as needed
        $lineCount = ceil($this->GetStringWidth($txt) / ($w - 2 * $this->cMargin));
        $estimatedHeight = $lineCount * $lineHeight;

        // Perform multicell with estimated height
        $this->MultiCell($w, $estimatedHeight, $txt, $border, $align, $fill);
    }
}
