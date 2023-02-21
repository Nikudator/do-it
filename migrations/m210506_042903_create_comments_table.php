<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210506_042903_create_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'master_id' => $this->integer()->notNull(),
            'body' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'parent_comment' => $this->integer()->notNull(),
            'answered_comment' => $this->integer()->notNull(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-comments-author_id}}',
            '{{%comments}}',
            'author_id'
        );

        $this->createIndex(
            '{{%idx-comments-master_id}}',
            '{{%comments}}',
            'master_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-comments-author_id}}',
            '{{%comments}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-comments-author_id}}',
            '{{%comments}}'
        );

        // drops index for column `master_id`
        $this->dropIndex(
            '{{%idx-comments-master_id}}',
            '{{%comments}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-comments-author_id}}',
            '{{%comments}}'
        );

        $this->dropTable('{{%comments}}');
    }
}
