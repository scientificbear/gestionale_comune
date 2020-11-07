<?php

// Include the main TCPDF library (search for installation path).
require_once '../../assets/libs/tcpdf/tcpdf.php';
require_once "../general/protect.php";
require_once "../general/config.php";

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = '../../assets/images/comune_verona.jpg';
        $this->Image($image_file,
            '', 20, # x, y dal bordo alto a sx
            50, # zoom
            '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        // $this->SetFont('helvetica', 'B', 20);
        // Title
        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        // $this->SetY(-15);
        // Set font
        // $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT ii.*,
    i.nome AS nome_immobile, i.indirizzo, i.circoscrizione, i.indirizzo, i.nome_ref, i.telefono_ref,
    ti.tipologia,
    d.nome AS nome_ditta, d.email, d.telefono_ref as telefono_ref_ditta
    FROM interventi_immobili ii
    LEFT JOIN immobili i ON ii.id_immobile=i.id
    LEFT JOIN tipo_immobili ti ON i.id_tipologia=ti.id
    LEFT JOIN ditte d ON ii.id_ditta=d.id
    WHERE ii.id = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // create new PDF document
                $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('U. O. Tecnico Circoscrizioni');
                $pdf->SetTitle('Dettagli intervento');
                // $pdf->SetSubject('TCPDF Tutorial');
                // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

                // set default header data
                $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                // set some language-dependent strings (optional)
                if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
                }

                // ---------------------------------------------------------

                // set font
                $pdf->SetFont('helvetica', '', 11);

                // add a page
                $pdf->AddPage();

                // Set some content to print
                $html = '
<h1 style="text-align:center">Ordine di servizio<br/></h1>
<style type="text/css">
.tg {width: 100%}
.tg td{}
.tg .sx{text-align:right; width: 15%; height: 24px}
.tg .dx{border-bottom-color:black;border-bottom-style:solid;border-bottom-width:1px}
</style>
<table class="tg">
  <tr>
    <td class="sx">A:</td>
    <td class="dx" colspan="2" style="width:85%"><b>'.$row["nome_ditta"].'</b></td>
  </tr>
  <tr>
    <td class="sx">Da:</td>
    <td class="dx" colspan="2">U.O. Tecnico Circoscrizioni </td>
  </tr>
  <!-- <tr>
    <td class="sx"></td>
    <td class="dx" colspan="2">Via Sogare, 3 - 37138 Verona - tel. 045.8492.312 - fax 045.8492.328</td>
  </tr> -->
  <tr>
    <td class="sx">Oggetto:</td>
    <td class="dx" colspan="2">Richiesta di intervento di manutenzione ordinaria su edifici comunali</td>
  </tr>
  <tr>
    <td class="sx">e-mail:</td>
    <td class="dx">'.$row["email"].'</td>
    <td class="dx">tel. '.$row["telefono_ref_ditta"].'</td>
  </tr>
</table>
<br/><br/><br/>
<table style="width:100%">
    <tr>
        <td style="width:15%">
        </td>
        <td style="width:85%">
        In riferimento al contratto in essere, inviamo il seguente
        <br/>
        </td>
    </tr>
    <tr>
        <td style="width:15%">
        </td>
        <td style="width:85%">
        <b>ORDINE DI SERVIZIO</b>
        </td>
    </tr>
    <tr>
        <td style="width:15%">
        </td>
        <td style="width:85%">
            <table style="width:100%">
                <tr>
                    <th style="width:25%">NUMERO</th>
                    <th colspan="2" style="width:75%">'.$row["id"].'</th>
                </tr>
                <tr>
                    <td>DATA</td>
                    <td colspan="2">'.$row["data"].'</td>
                </tr>
                <tr>
                    <td style="width:25%">IMMOBILE</td>
                    <td style="width:25%">circoscrizione</td>
                    <td style="width:50%">'.$row["circoscrizione"].'^</td>
                </tr>
                <tr>
                    <td></td>
                    <td>tipologia</td>
                    <td>'.$row["tipologia"].'</td>
                </tr>
                <tr>
                    <td></td>
                    <td>nome</td>
                    <td>'.$row["nome_immobile"].'</td>
                </tr>
                <tr>
                    <td></td>
                    <td>indirizzo</td>
                    <td>'.$row["indirizzo"].'</td>
                </tr>
                <tr>
                    <td>Per chiarimenti</td>
                    <td>referente</td>
                    <td>'.$row["nome_ref"].'</td>
                </tr>
                <tr>
                    <td></td>
                    <td>telefono</td>
                    <td>'.$row["telefono_ref"].'</td>
                </tr>
                <tr>
                    <td>Descrizione</td>
                    <td colspan="2">'.$row["descrizione"].'</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br/><br/><br/><br/><br/>

<table style="width:100%">
    <tr>
        <td style="width:60%">
        </td>
        <td style="width:40%; text-align:center">
        Il Direttore di lavori<br/>
        <br/>
        </td>
    </tr>
</table>

<br/>

<p>U.O. Tecnico Circoscrizioni<br/>
Piazza Brà 1 – 37121 Verona<br/>	
Tel. 045 8492311- 8492330, Fax 045 8492328<br/>
www.comune.verona.it<br/>
Email: coordinamentotecnicocircoscrizioni@comune.verona.it<br/>
Pec: coordinamento.tecnico.circoscrizione@pec.comune.verona.it<br/>
Codice Fiscale e Partita Iva 00215150236<br/>
Codice Univoco Ufficio: 3DE6QM<br/>
</p>';

                // Print text using writeHTMLCell()
                $pdf->writeHTMLCell('', '', '', 60, $html);

                // ---------------------------------------------------------

                //Close and output PDF document
                $pdf->Output('dettagli_intervento.pdf', 'I');
                }
            }
        }
};

?>