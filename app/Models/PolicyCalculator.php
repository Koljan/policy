<?php

	namespace App\Models;
	require_once( '../../app/Help/InstalmentsCollection.php' );
	require_once( '../../app/Models/Instalment.php' );

	use App\Help\InstalmentsCollection;
	use App\Models\Instalment;

	class PolicyCalculator
	{
		private $carValue;
		private $basePremium;
		private $commission;
		private $tax;
		private $nbOfInstalments;
		private $hourSubmitted;
		protected $instalments;


		/**
		 * PolicyCalculator constructor.
		 *
		 * @param int $carValue
		 * @param int $tax
		 * @param int $nbOfInstalments
		 * @param int $hourSubmitted
		 */
		public function __construct( int $carValue, int $tax, int $nbOfInstalments, int $hourSubmitted = NULL )
		{
			$this->carValue        = $carValue;
			$this->tax             = $tax;
			$this->nbOfInstalments = $nbOfInstalments;
			$this->hourSubmitted   = $hourSubmitted;
			$this->instalments     = new InstalmentsCollection();
		}


		public function getInstalments()
		{
			for ( $i = 1; $i <= $this->nbOfInstalments; $i ++ )
			{
				$instalmentValue = intdiv( $this->carValue, $this->nbOfInstalments );
				$instalmentValue += ( $i === $this->nbOfInstalments ) ? $this->carValue % $this->nbOfInstalments : 0;
				$this->instalments->addInstalment( new Instalment( $instalmentValue, $this->tax, $this->hourSubmitted ) );
			}

			return $this->instalments->all();
		}


		/**
		 * @return mixed
		 */
		public function getCarValue()
		{
			return $this->carValue;
		}


		/**
		 * @param mixed $carValue
		 *
		 * @return PolicyCalculator
		 */
		public function setCarValue( $carValue )
		{
			$this->carValue = $carValue;

			return $this;
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
		 * @return PolicyCalculator
		 */
		public function setBasePremium( $basePremium )
		{
			$this->basePremium = $basePremium;

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
		 * @return PolicyCalculator
		 */
		public function setCommission( $commission )
		{
			$this->commission = $commission;

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
		 * @param mixed $tax
		 *
		 * @return PolicyCalculator
		 */
		public function setTax( $tax )
		{
			$this->tax = $tax;

			return $this;
		}


		/**
		 * @return mixed
		 */
		public function getTotalCost()
		{
			return $this->totalCost;
		}


		/**
		 * @param mixed $totalCost
		 *
		 * @return PolicyCalculator
		 */
		public function setTotalCost( $totalCost )
		{
			$this->totalCost = $totalCost;

			return $this;
		}


		private $totalCost;
	}