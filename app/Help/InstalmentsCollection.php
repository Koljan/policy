<?php


	namespace App\Help;


	use App\Models\Instalment;

	class InstalmentsCollection
	{
		private $instalments = [];


		/**
		 * @return array
		 */
		public function all(): array
		{
			return $this->instalments;
		}


		/**
		 * @param Instalment $instalment
		 *
		 * @return InstalmentsCollection
		 */
		public function addInstalment( Instalment $instalment )
		{
			$this->instalments[] = $instalment;

			return $this;
		}

	}