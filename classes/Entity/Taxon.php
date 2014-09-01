<?php

/*
 * This file is part of the Indigo Taxonomy module.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Taxonomy\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Taxon
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Taxon
{
	use \Indigo\Doctrine\Field\Id;
	use \Indigo\Doctrine\Field\Name;
	use \Indigo\Doctrine\Field\Description;
	use \Indigo\Doctrine\Behavior\SoftDelete;
	use \Indigo\Doctrine\Behavior\Slug;
	use \Indigo\Doctrine\Behavior\Tree;

	/**
	 * @var string
	 */
	private $permalink;

	/**
	 * @var Taxonomy
	 */
	private $taxonomy;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->initTree();
	}

	/**
	 * Returns the permalink
	 *
	 * @return string
	 */
	public function getPermalink()
	{
		return $this->permalink;
	}

	/*
	 * Sets the permalink
	 *
	 * @param string $permalink
	 *
	 * @return this
	 */
	public function setPermalink($permalink)
	{
		$this->permalink = $permalink;

		return $this;
	}

	/**
	 * Returns the taxonomy
	 *
	 * @return Taxonomy
	 */
	public function getTaxonomy()
	{
		return $this->taxonomy;
	}

	/**
	 * Sets the taxonomy
	 *
	 * @param Taxonomy $taxonomy
	 *
	 * @return self
	 */
	public function setTaxonomy(Taxonomy $taxonomy = null)
	{
		$this->taxonomy = $taxonomy;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addChild(self $child)
	{
		if (!$this->hasChild($child))
		{
			$child->setTaxonomy($this->taxonomy);
		}

		return parent::addChild($child);
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeChild(self $child)
	{
		if ($this->hasChild($child))
		{
			$child->setTaxonomy(null);
		}

		parent::removeChild($child);
	}
}
