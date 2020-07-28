<?php
	namespace App\Controllers;
	require_once ('../../app/Models/PolicyCalculator.php');



	use App\Models\PolicyCalculator;

	class FrontController
	{
		function __construct()
		{
			$this->handleRequest();
		}

		public function handleRequest()
		{
			if ( isset( $_REQUEST['action'] ) && 'calculateInsurance' === $_REQUEST['action'] )
			{
				list( 'estimated-car-value' => $carValue, 'tax-percentage' => $taxPercentage, 'nb-of-instalments' => $nbOfInstalments, 'hour-submitted' => $hourSubmitted) = $_POST;
				$policyCalculcator = new PolicyCalculator((int)$carValue, (int)$taxPercentage, (int)$nbOfInstalments, (int)10);
				$policyInstalments = $policyCalculcator->getInstalments();
				$data = [];
				$policyData = [
					'carValue' => (int)$carValue,
					'basePremium' => 0,
					'commission' => 0,
					'tax' => 0,
					'totalCost' => 0,
				];
				foreach ($policyInstalments as $instalment)
				{
					$data['instalments'][] = $instalment;
					$policyData['basePremium'] += $instalment->getBasePremium();
					$policyData['policyCommissionPercent'] = $instalment->getInstalmentPolicyCommission();
					$policyData['commission'] += $instalment->getCommission();
					$policyData['taxPercent'] = $instalment->getTaxPercent();
					$policyData['tax'] += $instalment->getTax();
					$policyData['totalCost'] += $instalment->getCost();

				}
				$data['policyData'] = $policyData;

				$html = '<table class="table table-sm table-bordered">';
				$html .=   '<thead>';
				$html .=	  '<tr>';
				$html .=		 '<th scope="col"></th>';
				$html .=		 '<th scope="col">Policy</th>';
									for ($i = 1; $i <= count($data['instalments']); $i++)
									{
										$html .= '<th scope="col">' . $i . ' Instalment</th>';
									}
				$html .=	  '</tr>';
				$html .=   '</thead>';
				$html .=   '<tbody>';
				$html .=	  '<tr>';
				$html .=		 '<th scope="row">Value</th>';
				$html .=		 '<td class="text-right">'.number_format($data['policyData']['carValue'], 2).'</td>';
									for ($i = 0; $i < count($data['instalments']); $i++)
									{
										$html .= '<td></td>';
									}
				$html .=	  '</tr>';
				$html .=	  '<tr>';
				$html .=		 '<th scope="row">Base premium (' . $data['instalments'][0]->getInstalmentPolicyCommission() . '%)</th>';
				$html .=		 '<td class="text-right">' . number_format($data['policyData']['basePremium'],2) . '</td>';
									for ($i = 0; $i < count($data['instalments']); $i++)
									{
										$html .= '<td class="text-right">' . number_format($data['instalments'][$i]->getBasePremium(),2) . '</td>';
									}
				$html .=	  '</tr>';
				$html .=	  '<tr>';
				$html .=		 '<th scope="row">Commission ('. $data['instalments'][0]->getCommissionPercent() .'%)</th>';
				$html .=		 '<td class="text-right">' . number_format($data['policyData']['commission'],2) . '</td>';
									for ($i = 0; $i < count($data['instalments']); $i++)
									{
										$html .= '<td class="text-right">' . number_format($data['instalments'][$i]->getCommission(),2) . '</td>';
									}
				$html .=	  '</tr>';
				$html .=	  '<tr>';
				$html .=		 '<th scope="row">Tax ('. $data['policyData']['taxPercent'] .'%)</th>';
				$html .=		 '<td class="text-right">' . number_format($data['policyData']['tax'], 2) . '</td>';
									for ($i = 0; $i < count($data['instalments']); $i++)
									{
										$html .= '<td class="text-right">' . number_format($data['instalments'][$i]->getTax(),2) . '</td>';
									}
				$html .=	  '</tr>';
				$html .=	  '<tr>';
				$html .=		 '<th scope="row" class="total-cost-placeholder">Total cost</th>';
				$html .=		 '<td class="text-right">' . number_format($data['policyData']['totalCost'],2) . '</td>';
									for ($i = 0; $i < count($data['instalments']); $i++)
									{
										$html .= '<td class="text-right">' . number_format($data['instalments'][$i]->getCost(),2) . '</td>';
									}
				$html .=	  '</tr>';
				$html .=   '</tbody>';
				$html .='</table>';
				echo $html;
			}
		}
	}