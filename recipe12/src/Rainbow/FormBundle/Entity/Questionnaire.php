<?php

namespace Rainbow\FormBundle\Entity;

class Questionnaire
{

    protected $name;
    protected $questions = array();

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param array $questions
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
    }





}
