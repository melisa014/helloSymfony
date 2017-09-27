<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MigrationVersions
 *
 * @ORM\Table(name="migration_versions")
 * @ORM\Entity
 */
class MigrationVersions
{
    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="migration_versions_version_seq", allocationSize=1, initialValue=1)
     */
    private $version;


}

