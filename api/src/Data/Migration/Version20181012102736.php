<?php declare(strict_types=1);

namespace Api\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181012102736 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE video_videos (id UUID NOT NULL, author_id UUID NOT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, origin VARCHAR(255) NOT NULL, status VARCHAR(16) NOT NULL, publish_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, thumbnail_path VARCHAR(255) DEFAULT NULL, thumbnail_size_width INT DEFAULT NULL, thumbnail_size_height INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_11FDC4FFF675F31B ON video_videos (author_id)');
        $this->addSql('COMMENT ON COLUMN video_videos.create_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN video_videos.publish_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE video_video_files (id UUID NOT NULL, video_id UUID NOT NULL, path VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, size_width INT DEFAULT NULL, size_height INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ABD5F85A29C1004E ON video_video_files (video_id)');
        $this->addSql('CREATE TABLE video_authors (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE video_videos ADD CONSTRAINT FK_11FDC4FFF675F31B FOREIGN KEY (author_id) REFERENCES video_authors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE video_video_files ADD CONSTRAINT FK_ABD5F85A29C1004E FOREIGN KEY (video_id) REFERENCES video_videos (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE video_video_files DROP CONSTRAINT FK_ABD5F85A29C1004E');
        $this->addSql('ALTER TABLE video_videos DROP CONSTRAINT FK_11FDC4FFF675F31B');
        $this->addSql('DROP TABLE video_videos');
        $this->addSql('DROP TABLE video_video_files');
        $this->addSql('DROP TABLE video_authors');
    }
}
