SE_STANDARD_PATH="`pwd`/src/SocialEngine/SnifferRules/Standard/"
PHPCS=php ./vendor/bin/phpcs
PHPCBF=php ./vendor/bin/phpcbf
INSTALLED=$(shell ${PHPCS} -i)

sniff: check-standard
	${PHPCS} --colors --standard=SocialEngine --ignore=src/SocialEngine/SnifferRules/Standard src

sniff-fix: check-standard
	${PHPCBF} --colors --standard=SocialEngine --ignore=src/SocialEngine/SnifferRules/Standard src

check-standard: 
ifeq (,$(findstring SocialEngine, $(INSTALLED)))
	${PHPCS} --config-set installed_paths ${SE_STANDARD_PATH}
endif
