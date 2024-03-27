<?php

namespace App\Core;


abstract class Form
{
    /**
     * stock le code HTML du formulaire enb chaine de caractere
     *
     * @var string
     */
    protected string $formCode = '';


    /**
     * Crée la balise d'ouverture du formulaire
     *
     * @param string $action action du formulaire
     * @param string $method methode du formulaire (POST, GET)
     * @param array $attributs
     * @return Form
     */
    public function startForm(string $action, string $method, array $attributs = []): self
    {
        $this->formCode .= "<form action=\"$action\" method=\"$method\"";
        $this->formCode .= !empty($attributs) ? $this->addAttribute($attributs) . '>' : '>';

        return $this;
    }

    /**
     * ferme la balise HTML FORM
     *
     * @return self
     */
    public function endForm(): self
    {
        $this->formCode .= "</form>";

        return $this;
    }



    /**
     * start div
     *
     * @param array $attributs
     * @return self
     */
    public function startDiv(array $attributs = []): self
    {
        $this->formCode .= "<div";
        $this->formCode .= !empty($attributs) ? $this->addAttribute($attributs) . '>' : '>';

        return $this;
    }

    /**
     * end div
     * ferme la balise HTML DIV
     *
     * @return self
     */
    public function endDiv(): self
    {
        $this->formCode .= "</div>";

        return $this;
    }



    /**
     * label
     *
     * @param string $for
     * @param string $text
     * @param array $attributs
     * @return self
     */
    public function addLabel(string $for, string $text, array $attributs = []): self
    {
        $this->formCode .= "<label for=\"$for\"";
        $this->formCode .= !empty($attributs) ? $this->addAttribute($attributs) . '>' : '>';

        $this->formCode .= "$text</label>";

        return $this;
    }



    /**
     * add input
     *
     * @param string $type
     * @param string $name
     * @param array $attributs
     * @return self
     */
    public function addInput(string $type, string $name, array $attributs = []): self
    {
        $this->formCode .= "<input type=\"$type\" name =\"$name\"";
        $this->formCode .= !empty($attributs) ? $this->addAttribute($attributs) . '/>' : '/>';

        return $this;
    }



    /**
     * Ajoute les attributs envoyés à la balise html
     *
     * @param array $attributs Tableau associatif (['class' => 'form-control', 'required' => true])
     * @return string
     */
    public function addAttribute(array $attributs): string
    {
        $str = '';
        // On défini les attribut HTML courts
        $attributsCouts = ['checked', 'selected', 'required', 'disabled', 'readonluy', 'multiple', 'autofocus', 'novalidate', 'formnovalidate'];

        foreach ($attributs as $key => $value) {
            if (in_array($key, $attributsCouts)) {
                $str .= " $key";
            } else {
                $str .= " $key=\"$value\"";
            }
        }
        return $str;
    }



    /**
     * Validation du formulaire (si tous les champs sont remplis)
     *
     * @param array $form Tableau issu du formulaire ($_POST || $_GET)
     * @param array $champs Tableau listant les champs obligatoires (['email', 'password'])
     * @return bool
     */
    public function validate(array $form, array $champs): bool
    {
        // On boucle sur le tableau de champs obligateur

        foreach ($champs as $champ) {
            if (empty($form[$champ]) || strlen(trim($form[$champ])) === 0) {
                return false;
            }
        }
        // le formulaire n'ai pas vide on envoie true
        return true;
    }


    /**
     * Renvoie le code HTML du formilaire en format string
     *
     * @return string
     */
    public function createView(): string
    {
        return $this->formCode;
    }
}
