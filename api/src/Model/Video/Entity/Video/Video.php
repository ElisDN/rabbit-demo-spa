<?php

declare(strict_types=1);

namespace Api\Model\Video\Entity\Video;

use Api\Model\AggregateRoot;
use Api\Model\EventTrait;
use Api\Model\Video\Entity\Author\Author;
use Api\Model\Video\Entity\Video\Event\VideoCreated;
use Api\Model\Video\Entity\Video\Event\VideoFileAdded;
use Api\Model\Video\Entity\Video\Event\VideoPublished;
use Api\Model\Video\Entity\Video\Event\VideoRemoved;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="video_videos")
 */
class Video implements AggregateRoot
{
    use EventTrait;

    private const STATUS_DRAFT = 'draft';
    private const STATUS_ACTIVE = 'active';

    /**
     * @var VideoId
     * @ORM\Column(type="video_video_id")
     * @ORM\Id
     */
    private $id;
    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity="Api\Model\Video\Entity\Author\Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $author;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_date")
     */
    private $createDate;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $origin;
    /**
     * @var Thumbnail
     * @ORM\Embedded(class="Thumbnail")
     */
    private $thumbnail;
    /**
     * @var ArrayCollection|File[]
     * @ORM\OneToMany(targetEntity="File", mappedBy="video", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"size.height" = "ASC"})
     */
    private $files;
    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    private $status;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true, name="publish_date")
     */
    private $publishDate;

    public function __construct(VideoId $id, Author $author, \DateTimeImmutable $date, string $name, string $origin)
    {
        $this->id = $id;
        $this->author = $author;
        $this->createDate = $date;
        $this->name = $name;
        $this->origin = $origin;
        $this->files = new ArrayCollection();
        $this->status = self::STATUS_DRAFT;
        $this->recordEvent(new VideoCreated($this->id, $this->author->getId(), $this->origin));
    }

    public function edit(string $name): void
    {
        $this->name = $name;
    }

    public function setThumbnail(Thumbnail $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function addFile(string $path, string $format, Size $size): void
    {
        $this->files->add($file = new File($this, $path, $format, $size));
        $this->recordEvent(new VideoFileAdded($this->id, $this->author->getId(), $file));
    }

    public function publish(\DateTimeImmutable $date): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->publishDate = $date;
        $this->recordEvent(new VideoPublished($this->id, $this->author->getId()));
    }

    public function remove(): void
    {
        $this->recordEvent(new VideoRemoved($this->id, $this->author->getId(), $this->origin));
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): VideoId
    {
        return $this->id;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getThumbnail(): Thumbnail
    {
        return $this->thumbnail;
    }

    public function getPublishDate(): ?\DateTimeImmutable
    {
        return $this->publishDate;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files->toArray();
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
        if (!$this->thumbnail->getPath()) {
            $this->thumbnail = null;
        }
    }
}