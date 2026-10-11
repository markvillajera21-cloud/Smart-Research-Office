<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFulltextIndexes extends Migration
{
    public function up()
    {
        $prefix = $this->db->prefix;

        $this->db->query(
            "CREATE FULLTEXT INDEX ft_research_titles_title
             ON {$prefix}research_titles (title)"
        );

        $this->db->query(
            "CREATE FULLTEXT INDEX ft_researchers_title_abstract
             ON {$prefix}researchers (approved_research_title, abstract)"
        );

        $this->db->query(
            "CREATE FULLTEXT INDEX ft_researchers_fullname_author
             ON {$prefix}researchers (fullname, author)"
        );
    }

    public function down()
    {
        $prefix = $this->db->prefix;

        $this->db->query("DROP INDEX ft_research_titles_title ON {$prefix}research_titles");
        $this->db->query("DROP INDEX ft_researchers_title_abstract ON {$prefix}researchers");
        $this->db->query("DROP INDEX ft_researchers_fullname_author ON {$prefix}researchers");
    }
}
