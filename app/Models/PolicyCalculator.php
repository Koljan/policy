<?php

	namespace App\Models;
	require_once( '../../app/Help/InstalmentsCollection.php' );
	require_once( '../../app/Models/Instalment.php' );

	use App\Help\InstalmentsCollection;
	use App\Models\Instalment;
	use DateTime;
	use DateTimeZone;
	use Exception;

	class PolicyCalculator
	{
		const DEFAULT_BASE_PREMIUM = 11;
		const INCREASED_BASE_PREMIUM = 13;
		const DAY_OF_THE_WEEK_FRIDAY = 5;
		const HOUR_INCREASED_BASE_PREMIUM_APPLIED_FROM = 15;
		const HOUR_INCREASED_BASE_PREMIUM_APPLIED_TO = 20;

		private $carValue;
		private $basePremium = self::DEFAULT_BASE_PREMIUM;
		private $commission;
		private $tax;
		private $nbOfInstalments;
		private $instalments;
		private $totalCost;


		/**
		 * PolicyCalculator constructor.
		 *
		 * @param int $carValue
		 * @param int $tax
		 * @param int $nbOfInstalments
		 * @param int $utcTimeOffset
		 *
		 * @throws Exception
		 */
		public function __construct( int $carValue,
			int $tax,
			int $nbOfInstalments,
			int $utcTimeOffset = NULL )
		{

			$this->carValue        = $carValue;
			$this->tax             = $tax;
			$this->nbOfInstalments = $nbOfInstalments;
			$this->instalments     = new InstalmentsCollection();
			$this->basePremium     = ( $utcTimeOffset ) ? $this->calculateBasePremium( $utcTimeOffset ) : $this->basePremium;
		}


		public function getInstalments()
		{
			for ( $i = 1; $i <= $this->nbOfInstalments; $i ++ )
			{

				$instalmentValue = intdiv( $this->carValue, $this->nbOfInstalments );
				$instalmentValue += ( $i === $this->nbOfInstalments ) ? $this->carValue % $this->nbOfInstalments : 0;
				$this->instalments->addInstalment( new Instalment( $instalmentValue, $this->tax, $this->getBasePremium() ) );
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


		private function calculateBasePremium( int $utcTimeOffset = 0 ): int
		{
			$basePremium = $this->getBasePremium();

			$utcTimeOffset = $utcTimeOffset == 0 ? 0 : - $utcTimeOffset;
			$timezoneName  = timezone_name_from_abbr( "", $utcTimeOffset * 60, false );
			$time          = new DateTime( 'now', new DateTimeZone( $timezoneName ) );
			if (
				self::DAY_OF_THE_WEEK_FRIDAY === (int) $time->format( 'N' )
				 && ( self::HOUR_INCREASED_BASE_PREMIUM_APPLIED_FROM <= (int) $time->format( 'G' ) )
				 && ( (int) $time->format( 'G' ) < self::HOUR_INCREASED_BASE_PREMIUM_APPLIED_TO )
			)
			{
				$basePremium = self::INCREASED_BASE_PREMIUM;
			}

			return $basePremium;
		}


	}