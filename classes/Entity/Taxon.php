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

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="taxons")
 * @Gedmo\Tree(type="nested")
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
	use \Indigo\Doctrine\Behavior\Tree {
		addChild as addChildOverride;
		removeChild as removeChildOverride;
	}

	/**
	 * @var string
	 *
	 * @Gedmo\Slug(handlers={
	 *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
	 *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
	 *          @Gedmo\SlugHandlerOption(name="separator", value="/")
	 *      })
	 * }, fields={"name"}, unique=true)
	 * @ORM\Column(type="string", unique=true)
	 */
	private $permalink;

	/**
	 * @var Taxonomy
	 *
	 * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\Taxonomy")
	 * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
	 */
	private $taxonomy;

	/**
	 * @var self
	 *
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\Taxon", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $parent;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Taxonomy\Entity\Taxon", mappedBy="parent", cascade={"all"})
	 * @ORM\OrderBy({"left" = "ASC"})
	 */
	private $children;

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

		return $this->addChildOverride($child);
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

		$this->removeChildOverride($child);
	}
}
