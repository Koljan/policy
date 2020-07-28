<?php

	$userName                = 'Nikolai Pulkkinen';
	$userNameCharactersArray = str_split( $userName );

	foreach ( $userNameCharactersArray as $character)
	{
		echo $character;
	}