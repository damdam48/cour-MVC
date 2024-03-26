<?php

namespace App\Models;
use App\Models\Model;

class User extends Models
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $firsName = null,
        protected ?string $lastName = null,
        protected ?string $email = null,
        protected ?string $password = null,

    )
    {
        $this->table = 'users';
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
         * Get the value of firsName
         *
         * @return ?string
         */
        public function getFirsName(): ?string
        {
                return $this->firsName;
        }

        /**
         * Set the value of firsName
         *
         * @param ?string $firsName
         *
         * @return self
         */
        public function setFirsName(?string $firsName): self
        {
                $this->firsName = $firsName;

                return $this;
        }

        /**
         * Get the value of lastName
         *
         * @return ?string
         */
        public function getLastName(): ?string
        {
                return $this->lastName;
        }

        /**
         * Set the value of lastName
         *
         * @param ?string $lastName
         *
         * @return self
         */
        public function setLastName(?string $lastName): self
        {
                $this->lastName = $lastName;

                return $this;
        }

        /**
         * Get the value of email
         *
         * @return ?string
         */
        public function getEmail(): ?string
        {
                return $this->email;
        }

        /**
         * Set the value of email
         *
         * @param ?string $email
         *
         * @return self
         */
        public function setEmail(?string $email): self
        {
                $this->email = $email;

                return $this;
        }

        /**
         * Get the value of password
         *
         * @return ?string
         */
        public function getPassword(): ?string
        {
                return $this->password;
        }

        /**
         * Set the value of password
         *
         * @param ?string $password
         *
         * @return self
         */
        public function setPassword(?string $password): self
        {
                $this->password = $password;

                return $this;
        }
}