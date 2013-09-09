<?php

/**
 * @author David Stansfield
 * @copyright 2011 Clix Media
 */
 
class Ppi_template extends Reports
{
  public function run()
  {
	  $results = array();
	  
	  $results['Content']  = "[Creditors Name]<br />[Creditors Address]<br />[Creditors Address]<br />[Creditors Address]<br />[Creditors Post Code]<br /><br />";
	  
	  $results['Content'] .= "<b>Date:</b> " . date("") . " 5th December 2011<br /><br />";
	  
	  $results['Content'] .= "<b>Account Number:</b> [Add Your Account Number Here]<br /><br />";
		
		$results['Content'] .= "<b>Customer Name:</b>  [Add Your Full Name Here]<br /><br />";
		
		$results['Content'] .= "Dear Sir or Madam<br /><br />";
		
		$results['Content'] .= "We/I believe I may have purchased a payment protection insurance policy from you. The account number is stated above. We/I have recently become aware that we/I were mis-sold the insurance policy for the following reasons:-<br /><br />"; 
		
		$results['Content'] .= "1) We/I did not request the ppi policy and felt pressured into buying it.<br />2) We/I were lead to believe that the ppi policy had to be provided by you in order for us to get the loan and that it was compulsory<br />3) We/I were not informed we/I could buy the insurance elsewhere and that we/I could potentially find it cheaper.<br />4) We/I did not have the separate costs of the PPI policy and the loan explained to us.<br />5) We/I did not have the exclusions and restrictions on the policy fully explained to us.<br />6) The terms and conditions of the ppi and there importance were not fully explained<br />7) We/I were not told the sale would generate a sales commission.<br />8) We/I were not asked if we/I already had insurance to cover the repayments and finally,<br /><br />";
		
		$results['Content'] .= "Unless you can justify to us that the policy sale was fair and reasonable we are requesting a full refund of all premiums paid and the subsequent interest on these payments.
		We expect that 8% statutory interest, the amount a court would award in such a situation, to be added to each payment awarded.
		We look forward to a full and prompt response and for the matter to be concluded in eight weeks or we shall contact the Financial Ombudsman and request we/I investigate the complaint further.<br /><br />";
		
		$results['Content'] .= "Yours sincerely<br /><br /><br /><br />";
		$results['Content'] .= html_entity_decode($this->value[0]);
    
    $outputMethod = $this->output;
    return $this->outputReport($this->$outputMethod($results));
  }
  
  private function pdf($results)
  {
  	$pdf = new fpdf_html();
		// First page
		$pdf->AddPage();
		$pdf->SetFont('Arial','',12);
		
		$pdf->WriteHTML($results['Content']);
		 
		 return $pdf->Output();
  }
}
?>