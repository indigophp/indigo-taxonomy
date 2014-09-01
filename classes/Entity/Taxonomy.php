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
 * @ORM\Entity
 * @ORM\Table(name="taxonomies")
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Taxonomy
{
	use \Indigo\Doctrine\Field\Id;
	use \Indigo\Doctrine\Field\Name;
	use \Indigo\Doctrine\Field\Description;

	/**
	 * @var Taxon
	 *
	 * @ORM\ManyToOne(targetEntity="Taxon", cascade={"all"})
	 * @ORM\JoinColumn(name="root_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $root;

	/**
	 * Returns the root
	 *
	 * @return Taxon
	 */
	public function getRoot()
	{
		return $this->root;
	}

	/**
	 * Sets the root
	 *
	 * @param Taxon $root
	 *
	 * @return self
	 */
	public function setRoot(Taxon $root = null)
	{
		$this->root = $root;

		return $this;
	}
}
