<?php

namespace App\Models;

use App\Models\Model;
use DateTime;

class Categorie extends Model
{
        public function __construct(
                protected ?int $id = null,
                protected ?string $title = null,
                protected ?int $enable = null,
                protected ?string $slug = null,
                protected ?DateTime $createdAt = null,
                protected ?DateTime $updatedAt = null,

        ) {
                $this->table = 'categories';
        }

        public function setCreatedAt(null|string|DateTime $createdAt): self
        {
                if (is_string($createdAt)) {
                        $createdAt = new DateTime($createdAt);
                }

                $this->createdAt = $createdAt;

                return $this;
        }

        public function setUpdatedAt(null|DateTime|string $updatedAt): self
        {
                if (is_string($updatedAt)) {
                        $updatedAt = new DateTime($updatedAt);
                }

                $this->updatedAt = $updatedAt;

                return $this;
        }

        private static function slugify($text, string $divider = '-')
        {
                // replace non letter or digits by divider
                $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

                // transliterate
                $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

                // remove unwanted characters
                $text = preg_replace('~[^-\w]+~', '', $text);

                // trim
                $text = trim($text, $divider);

                // remove duplicate divider
                $text = preg_replace('~-+~', $divider, $text);

                // lowercase
                $text = strtolower($text);

                if (empty($text)) {
                        return 'n-a';
                }

                return $text;
        }



        /**
         * Get the value of id
         *
         * @return ?int
         */
        public function getId(): ?int
        {
                return $this->id;
        }

        /**
         * Set the value of id
         *
         * @param ?int $id
         *
         * @return self
         */
        public function setId(?int $id): self
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of title
         *
         * @return ?string
         */
        public function getTitle(): ?string
        {
                return $this->title;
        }

        /**
         * Set the value of title
         *
         * @param ?string $title
         *
         * @return self
         */
        public function setTitle(?string $title): self
        {
                $this->title = $title;

                return $this;
        }

        /**
         * Get the value of enable
         *
         * @return ?int
         */
        public function getEnable(): ?int
        {
                return $this->enable;
        }

        /**
         * Set the value of enable
         *
         * @param ?int $enable
         *
         * @return self
         */
        public function setEnable(?int $enable): self
        {
                $this->enable = $enable;

                return $this;
        }

        /**
         * Get the value of slug
         *
         * @return ?string
         */
        public function getSlug(): ?string
        {
                return $this->slug;
        }

        /**
         * Set the value of slug
         *
         * @param ?string $slug
         *
         * @return self
         */
        public function setSlug(?string $slug): self
        {
                $this->slug = $slug;

                return $this;
        }

        /**
         * Get the value of createdAt
         *
         * @return ?DateTime
         */
        public function getCreatedAt(): ?DateTime
        {
                return $this->createdAt;
        }



        /**
         * Get the value of updatedAt
         *
         * @return ?DateTime
         */
        public function getUpdatedAt(): ?DateTime
        {
                return $this->updatedAt;
        }
}
