<?php
namespace common\template;

/**
 * Each template is required to have an Identifier associated to it. Simply extend this class, and
 * use {@link TemplateIdentifier::getId} to get the id for the template. See
 * {@link EmptyTemplate} for an example on how to create templates.
 */
abstract class TemplateIdentifier {
	/**
	 * Gets the identifier for this template.
	 *
	 * @return string
	 */
	public static function getId(): string {
		return static::class;
	}
}
