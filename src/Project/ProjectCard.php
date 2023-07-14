<?php

namespace App\Project;

class ProjectCard
{
    /**
     *  @var int $value
     */
    protected $value; //14
    /**
     *  @var string $type
     */
    protected $type; //Spades Diamonds Hearts Clubs

    public function ConstructorWithArgument1(string $card_name)
    {
        $vals = explode("_", $card_name);
        $compare = ["jack" => 11, "queen" => 12, "king" => 13, "ace" => 14];
        $this->value = (int)$vals[0];
        if (array_key_exists($vals[0], $compare)) {
            $this->value = $compare[$vals[0]];
        }

        $this->type = $vals[2];
    }

    public function ConstructorWithArgument2(int $value, string $type)
    {
        $this->value = $value;
        $this->type = $type;
    }


    public function __construct()
    {
        $arguments = func_get_args();
        $numberOfArguments = func_num_args();

        if (method_exists(
            $this,
            $function =
                'ConstructorWithArgument' . $numberOfArguments
        )) {
            call_user_func_array(
                array($this, $function),
                $arguments
            );
        }
    }



    public function getValue(): int | null
    {
        return $this->value;
    }

    public function getType(): string | null
    {
        return $this->type;
    }
    /**
     * @return array{"value": mixed, "type": mixed}
     */
    public function getAll(): array
    {
        $deck = [
            "value" => $this->getValue(),
            "type" => $this->getType(),
        ];
        return $deck;
    }
}
