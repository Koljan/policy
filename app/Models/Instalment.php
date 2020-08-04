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


		public function __construct( int $productBaseValue, int $taxPercent, int $instalmentPolicyCommission )
		{
			$this->productBaseValue = $productBaseValue;
			$this->taxPercent       = $taxPercent;

			$this->setInstalmentPolicyCommission( $instalmentPolicyCommission );
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
		 * @return Instalment
		 */
		private function setBasePremium()
		{
			$this->basePremium = $this->productBaseValue * ( $this->instalmentPolicyCommission / 100 );

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
		 * @return Instalment
		 */
		private function setCommission()
		{
			$this->commission = $this->basePremium * ( $this->commissionPercent / 100 );

			return $this;
		}


		/**
		 * @return Instalment
		 */
		public function setTax()
		{
			$this->tax = $this->basePremium * ( $this->taxPercent / 100 );

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
		 * @return Instalment
		 */
		public function setCost()
		{
			$this->cost = $this->basePremium + $this->commission + $this->tax;

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
				'basePremium' => $this->getBasePremium(),
				'commission'  => $this->getCommission(),
				'cost'        => $this->getCost(),
				'tax'         => $this->getTax(),
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