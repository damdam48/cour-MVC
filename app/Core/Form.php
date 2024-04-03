<?php

namespace App\Core;

/**
 * Class générique de génération de formulaire
 */
abstract class Form
{
    /**
     * Stock le code HTML du formulaire en chaine de caractère
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
     * Ferme la balise HTML form
     *
     * @return self
     */
    public function endForm(): self
    {
        $this->formCode .= "</form>";

        return $this;
    }

    /**
     * Ajoute une balise d'ouverture HTML div
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
     * Ajoute une balise de fermeture HTML div
     *
     * @return self
     */
    public function endDiv(): self
    {
        $this->formCode .= "</div>";

        return $this;
    }

    /**
     * Ajoute un label
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
     * Ajoute un input
     *
     * @param string $type
     * @param string $name
     * @param array $attributs
     * @return self
     */
    public function addInput(string $type, string $name, array $attributs = []): self
    {
        $this->formCode .= "<input type=\"$type\" name=\"$name\"";

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

        // On définit les attributs HTML courts
        $attributsCourts = ['checked', 'selected', 'required', 'disabled', 'readonly', 'multiple', 'autofocus', 'novalidate', 'formnovalidate'];

        // On boucle sur le taleau d'attribut
        foreach ($attributs as $key => $value) {
            if ($value) {
                if (in_array($key, $attributsCourts)) {
                    $str .= " $key";
                } else {
                    // On ajoute l'attribut = la valeur
                    $str .= " $key='$value'";
                }
            }
        }

        return $str;
    }

    /**
     * Ajout un textarea
     *
     * @param string $name
     * @param array $attributs
     * @param string|null $value
     * @return self
     */
    public function addTextarea(string $name, array $attributs = [], ?string $value = null): self
    {
        $this->formCode .= "<textarea name=\"$name\"";

        $this->formCode .= !empty($attributs) ? $this->addAttribute($attributs) . '>' : '>';

        $this->formCode .= $value . '</textarea>';

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param array $choices [
     *      [
     *          '1' => [
     *              'label' => 'Pierre',
     *              'attributs' => ['class' => 'toto']
     *          ],
     *          '2' => [
     *              'label' => 'Paul',
     *              'attributs' => ['class' => 'toto']
     *          ],
     *      ]
     * ]
     * @param array $attributs
     * @return self
     */
    public function addSelect(string $name, array $choices, array $attributs = []): self
    {
        $this->formCode .= "<select name=\"$name\"";

        $this->formCode .= !empty($attributs) ? $this->addAttribute($attributs) . '>' : '>';

        foreach ($choices as $value => $choice) {
            $this->formCode .= "<option value=\"$value\"";
            $this->formCode .= !empty($choice['attributs']) ? $this->addAttribute($choice['attributs']) . '>' : '>';
            $this->formCode .= "$choice[label]</option>";
        }

        $this->formCode .= '</select>';

        return $this;
    }

    /**
     * Ajoute un bouton
     *
     * @param string $text
     * @param array $attributs
     * @return self
     */
    public function addButton(string $text, array $attributs = []): self
    {
        $this->formCode .= "<button";

        $this->formCode .= !empty($attributs) ? $this->addAttribute($attributs) . '>' : '>';

        $this->formCode .= "$text</button>";

        return $this;
    }

    /**
     * Validation du formulaire (si tous les champs sont remplis)
     *
     *  if (!empty($_POST['email']))
     * @param array $form Tableau issu du formulaire ($_POST || $_GET)
     * @param array $champs Tableau listant les champs obligatoires (['email', 'password'])
     * @return bool
     */
    public function validate(array $form, array $champs): bool
    {
        // On boucle sur le tableau de champs obligatoires
        foreach ($champs as $champ) {
            // On vérifie si les champs obligatoires sont null
            if (empty($form[$champ]) || strlen(trim($form[$champ])) === 0) {
                // Si oui, on retourne false car le formulaire n'est pas valide
                return false;
            }
        }

        // Le formulaire est valide on envoie true
        return true;
    }

    /**
     * Renvoie le code HTML du formulaire en format string
     *
     * @return string
     */
    public function createView(): string
    {
        return $this->formCode;
    }
}
