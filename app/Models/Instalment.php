<?php


	namespace App\Models;

	use JsonSerializable;


	class Instalment implements JsonSerializable
	{
		private $productBaseValue;
		private $commission;
		private $cost;
		private $instalmentPolicyCommission;
		private $basePremium;
		private $commissionPercent = 17;
		private $taxPercent;
		private $tax;


		public function __construct( int $productBaseValue, int $taxPercent, int $instalmentPolicyComission )
		{
			$this->productBaseValue  = $productBaseValue;
			$this->taxPercent        = $taxPercent;

			$this->setInstalmentPolicyCommission($instalmentPolicyComission);
			$this->calculateInstalment();
		}


		private function calculateInstalment()
		{
			$this->setBasePremium();
			$this->setCommission();
			$this->setTax();
			$this->setCost();
		}


		/**
		 * @return mixed
		 */
		public function getBasePremium()
		{
			return $this->basePremium;
		}


		/**
		 * @param mixed $basePremium
		 *
		 * @return Instalment
		 */
		private function setBasePremium()
		{
			$this->basePremium = round( $this->productBaseValue * ( $this->instalmentPolicyCommission / 100 ), 2 );

			return $this;
		}


		/**
		 * @return mixed
		 */
		public function getCommission()
		{
			return $this->commission;
		}


		/**
		 * @param mixed $commission
		 *
		 * @return Instalment
		 */
		private function setCommission()
		{
			$this->commission = round( $this->basePremium * ( $this->commissionPercent / 100 ), 2 );

			return $this;
		}


		/**
		 * @param mixed $tax
		 *
		 * @return Instalment
		 */
		public function setTax()
		{
			$this->tax = round( $this->basePremium * ( $this->taxPercent / 100 ), 2 );

			return $this;
		}


		/**
		 * @return mixed
		 */
		public function getTax()
		{
			return $this->tax;
		}


		/**
		 * @param mixed $cost
		 *
		 * @return Instalment
		 */
		public function setCost()
		{
			$this->cost = round( $this->basePremium + $this->commission + $this->tax, 2 );

			return $this;
		}


		/**
		 * @return mixed
		 */
		public function getCost()
		{
			return $this->cost;
		}


		/**
		 * @return int
		 */
		public function getInstalmentPolicyCommission(): int
		{
			return $this->instalmentPolicyCommission;
		}


		/**
		 * @return int
		 */
		public function getTaxPercent(): int
		{
			return $this->taxPercent;
		}


		/**
		 * @return int
		 */
		public function getCommissionPercent(): int
		{
			return $this->commissionPercent;
		}


		public function jsonSerialize()
		{
			return [
				'basePremium' => round( $this->getBasePremium(), 1 ),
				'commission'  => round( $this->getCommission(), 1 ),
				'cost'        => round( $this->getCost(), 1 ),
				'tax'         => round( $this->getTax(), 1 ),
			];

		}


		/**
		 * @param mixed $instalmentPolicyCommission
		 *
		 * @return Instalment
		 */
		public function setInstalmentPolicyCommission( $instalmentPolicyCommission )
		{
			$this->instalmentPolicyCommission = $instalmentPolicyCommission;

			return $this;
		}

	}