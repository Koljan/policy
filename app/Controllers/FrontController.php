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
					'carValue' => $carValue,
					'basePremium' => 0,
					'commission' => 0,
					'tax' => 0,
					'totalCost' => 0,
				];
				foreach ($policyInstalments as $instalment)
				{
					$data[] = $instalment;
					$policyData['basePremium'] += $instalment->getBasePremium();
					$policyData['policyCommissionPercent'] = $instalment->getInstalmentPolicyCommission();
					$policyData['commission'] += $instalment->getCommission();
					$policyData['taxPercent'] = $instalment->getTaxPercent();
					$policyData['tax'] += $instalment->getTax();
					$policyData['totalCost'] += $instalment->getCost();

				}
				$data[] = $policyData;
				echo json_encode($data);
			}
		}
	}