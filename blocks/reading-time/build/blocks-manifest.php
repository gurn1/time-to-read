<?php
// This file is generated. Do not modify it manually.
return array(
	'reading-time' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/reading-time',
		'version' => '0.1.0',
		'title' => 'Reading Time',
		'category' => 'widgets',
		'icon' => 'clock',
		'description' => 'Show how long it takes to read this post on average.',
		'example' => array(
			
		),
		'supports' => array(
			'align' => true,
			'color' => array(
				'text' => true,
				'background' => false
			),
			'typography' => array(
				'fontSize' => true
			)
		),
		'attributes' => array(
			'readingTime' => array(
				'type' => 'string',
				'default' => ''
			),
			'textColor' => array(
				'type' => 'string'
			),
			'customTextColor' => array(
				'type' => 'string'
			)
		),
		'textdomain' => 'reading-time',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css'
	)
);
