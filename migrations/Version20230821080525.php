<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821080525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_articles DROP FOREIGN KEY FK_69239851A21214B7');
        $this->addSql('ALTER TABLE categories_articles DROP FOREIGN KEY FK_692398511EBAF6CC');
        $this->addSql('ALTER TABLE tags_articles DROP FOREIGN KEY FK_D54BAD718D7B4FB4');
        $this->addSql('ALTER TABLE tags_articles DROP FOREIGN KEY FK_D54BAD711EBAF6CC');
        $this->addSql('DROP TABLE categories_articles');
        $this->addSql('DROP TABLE tags_articles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories_articles (categories_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_692398511EBAF6CC (articles_id), INDEX IDX_69239851A21214B7 (categories_id), PRIMARY KEY(categories_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tags_articles (tags_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_D54BAD718D7B4FB4 (tags_id), INDEX IDX_D54BAD711EBAF6CC (articles_id), PRIMARY KEY(tags_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE categories_articles ADD CONSTRAINT FK_69239851A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_articles ADD CONSTRAINT FK_692398511EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_articles ADD CONSTRAINT FK_D54BAD718D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_articles ADD CONSTRAINT FK_D54BAD711EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
    }
}
