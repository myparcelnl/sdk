# Changelog

All notable changes to this project will be documented in this file. See
[Conventional Commits](https://conventionalcommits.org) for commit guidelines.

## [7.1.0](https://github.com/myparcelnl/sdk/compare/v7.0.0...v7.1.0) (2022-02-09)


### :sparkles: New Features

* **pps:** add drop-off point to order ([#387](https://github.com/myparcelnl/sdk/issues/387)) ([0722275](https://github.com/myparcelnl/sdk/commit/0722275ea724584b680c68786f435f809332d6b2))
* **pps:** add physical_properties to order ([a474cde](https://github.com/myparcelnl/sdk/commit/a474cdef040a5a5d57ea171f24ec8c82fdd6b5f6))


### :bug: Bug Fixes

* **pps:** accept null as dropoffpoint to avoid errors ([#391](https://github.com/myparcelnl/sdk/issues/391)) ([436af33](https://github.com/myparcelnl/sdk/commit/436af33093734d118e9bbb4c8c625c44f860155f))
* **pps:** datetime throwing error when null ([#385](https://github.com/myparcelnl/sdk/issues/385)) ([f3dd238](https://github.com/myparcelnl/sdk/commit/f3dd2383f3b89213a840853cdca2506f0e874514))
* **pps:** make number suffix in drop off point non-nullable ([#397](https://github.com/myparcelnl/sdk/issues/397)) ([395d5e5](https://github.com/myparcelnl/sdk/commit/395d5e5cd0c0bea89cc0431ca2c32ae76347eea3))
* use proprietary variable name in env ([#388](https://github.com/myparcelnl/sdk/issues/388)) ([98b6d7e](https://github.com/myparcelnl/sdk/commit/98b6d7eda2a6be3de296a67c9b298365fcbcf101))

## [7.0.0](https://github.com/myparcelnl/sdk/compare/v6.1.0...v7.0.0) (2022-02-09)


### ⚠ BREAKING CHANGES

* **instabox:** rename rjp to instabox (#348)

* **instabox:** rename rjp to instabox ([#348](https://github.com/myparcelnl/sdk/issues/348)) ([e1af3ce](https://github.com/myparcelnl/sdk/commit/e1af3ce859398eaa8db8b387199c9258160fae87))


### :sparkles: New Features

* **instabox:** add same day delivery ([#380](https://github.com/myparcelnl/sdk/issues/380)) ([e0725a9](https://github.com/myparcelnl/sdk/commit/e0725a9128ce452417c19b8af489faaf575a7e5a))
* **pps:** add delivery type to request body ([579925f](https://github.com/myparcelnl/sdk/commit/579925fe46b7d06ee9bad8eb4f7315fa5574dfc3))
* **pps:** add drop-off point to order ([#387](https://github.com/myparcelnl/sdk/issues/387)) ([5821c84](https://github.com/myparcelnl/sdk/commit/5821c8416c9ff3a3a5661a30f2eea33acbbdda19))
* **pps:** add physical_properties to order ([cd348f7](https://github.com/myparcelnl/sdk/commit/cd348f761b7180cfd0dcc664eb671cd685da2283))


### :bug: Bug Fixes

* **collection:** remove php8 deprecation warnings ([#376](https://github.com/myparcelnl/sdk/issues/376)) ([790c22b](https://github.com/myparcelnl/sdk/commit/790c22bbb524bce19da0a623d9f5e38f3b2d9bb6))
* **pps:** accept null as dropoffpoint to avoid errors ([#391](https://github.com/myparcelnl/sdk/issues/391)) ([8aff9a1](https://github.com/myparcelnl/sdk/commit/8aff9a1bb197167b58872184a3f944785b95e953))
* **pps:** datetime throwing error when null ([#385](https://github.com/myparcelnl/sdk/issues/385)) ([cafde44](https://github.com/myparcelnl/sdk/commit/cafde44c0bf39f35252f20a857d71f77715eb562))
* **pps:** fix export delivery options ([#369](https://github.com/myparcelnl/sdk/issues/369)) ([aabdb56](https://github.com/myparcelnl/sdk/commit/aabdb5681aa897b22cbc99a64a3a0075bf909d36))
* **pps:** make number suffix in drop off point non-nullable ([#397](https://github.com/myparcelnl/sdk/issues/397)) ([7fb25fe](https://github.com/myparcelnl/sdk/commit/7fb25fed5a862f8be7ad66a871cd03962c434646))
* use proprietary variable name in env ([#388](https://github.com/myparcelnl/sdk/issues/388)) ([2e6a84a](https://github.com/myparcelnl/sdk/commit/2e6a84a66d0912aa0b94fb7525328f5207061851))

## [6.1.0](https://github.com/myparcelnl/sdk/compare/v6.0.0...v6.1.0) (2021-12-31)


### :bug: Bug Fixes

* **consignment:** only validate delivery date if needed ([#364](https://github.com/myparcelnl/sdk/issues/364)) ([2f34c53](https://github.com/myparcelnl/sdk/commit/2f34c53cfa2d7528321f55aadbdde9f516094416))
* **pps:** export delivery options ([#360](https://github.com/myparcelnl/sdk/issues/360)) ([c333346](https://github.com/myparcelnl/sdk/commit/c333346f2c3e59b7f0e08782dcd1974d11d6ca13))
* **pps:** export order date with a timestamp ([09eca9c](https://github.com/myparcelnl/sdk/commit/09eca9c668d5ce64fba30f61fde393e85b7fe331))
* **pps:** export Rest of World orders ([#358](https://github.com/myparcelnl/sdk/issues/358)) ([97da682](https://github.com/myparcelnl/sdk/commit/97da682f2b7528ba49b9edd6bb64307c3b49df6f))


### :sparkles: New Features

* **consignment:** make email nullable ([#373](https://github.com/myparcelnl/sdk/issues/373)) ([867b54a](https://github.com/myparcelnl/sdk/commit/867b54a69b8a8ad1d37357565af20174b4915725))
* **pps:** allow setting of label description ([289d0d1](https://github.com/myparcelnl/sdk/commit/289d0d1e830adf8d476b2aa1465dd156f2eca1fa))
* **recipient:** add region property ([#367](https://github.com/myparcelnl/sdk/issues/367)) ([32a1d38](https://github.com/myparcelnl/sdk/commit/32a1d38bbd8bce4798cf6e9682bb52922c380258))
* **recipient:** add setFullStreet ([#349](https://github.com/myparcelnl/sdk/issues/349)) ([9f40418](https://github.com/myparcelnl/sdk/commit/9f40418d2a52b8d7d984f06858c0e78d55c5fc0d))
