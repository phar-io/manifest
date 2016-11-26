<?php

namespace PharIo\Manifest;

class AuthorElementCollection extends ElementCollection {

    public function current() {
        return new AuthorElement(
            $this->getCurrentElement()
        );
    }

}
