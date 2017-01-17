<?php

namespace AdamApp;

class Comment {

  protected $database;
  protected $name;
  protected $email;
  protected $comment;
  protected $submissionDate;

  public function __construct(\medoo $medoo) {
    $this->database = $medoo;
  }

  public function findAll() {
    $collection = [ ];
    $comments = $this->database->select('comments', null, '*',
                                        ["ORDER" => "comments.submissionDate DESC"]);
    if ($comments) {
      foreach ($comments as $array) {
        $comment = new self($this->database);
        $collection[] = $comment
                        ->setComment($array['comment'])
                        ->setEmail($array['email'])
                        ->setName($array['name'])
                        ->setSubmissionDate($array['submissionDate']);
      }
    }

    return ($collection);
  }

  /**
   * Property: name
   */
  public function getName() {
    return $this->name;
  }
  public function setName($name) {
    if ($this->name == $name) return null;
    $this->name = $name;
    return $this;
  }

  /**
   * Property: email
   */
  public function getEmail() {
    return $this->email;
  }
  public function setEmail($email) {
    if ($this->email == $email) return null;
    $this->email = $email;
    return $this;
  }

  /**
   * Property: comment
   */
  public function getComment() {
    return $this->comment;
  }
  public function setComment($comment) {
    if ($this->comment == $comment) return null;
    $this->comment = $comment;
    return $this;
  }

  /**
   * Property: submissionDate
   */
  public function getSubmissionDate() {
    return $this->submissionDate;
  }
  public function setSubmissionDate($submissionDate) {
    if ($this->submissionDate == $submissionDate) return null;
    $this->submissionDate = $submissionDate;
    return $this;
  }

  public function save() {
    if ($this->getName() && $this->getEmail() && $this->getComment()) {
      $this->setSubmissionDate(date('Y-m-d H:i:s'));
      return $this->database->insert('comments', [
        'name'           => $this->getName(),
        'email'          => $this->getEmail(),
        'comment'        => $this->getComment(),
        'submissionDate' => $this->getSubmissionDate()
      ]);
    }
    throw new \Exception("Failed to save comment.");
  }

}