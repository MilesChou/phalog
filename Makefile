#!/usr/bin/make -f

.PHONY: all clean clean-all check test analyse coverage example

# ---------------------------------------------------------------------

all: test analyse

clean:
	rm -rf ./build

clean-all: clean
	rm -rf ./vendor
	rm -rf ./composer.lock

check:
	php vendor/bin/phpcs

test: clean check
	phpdbg -qrr vendor/bin/phpunit

analyse:
	php vendor/bin/phpstan analyse src --level=6

coverage: test
	@if [ "`uname`" = "Darwin" ]; then open build/coverage/index.html; fi

example:
	rm -rf dist
	php bin/phalog.php -vv --input-dir=examples build
