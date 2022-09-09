# Changelog

All notable changes to this project will be documented in this file. See
[Conventional Commits](https://conventionalcommits.org) for commit guidelines.

## [7.4.4](https://github.com/myparcelnl/sdk/compare/v7.4.3...v7.4.4) (2022-09-09)


### :bug: Bug Fixes

* remove lockfile ([563fd76](https://github.com/myparcelnl/sdk/commit/563fd76201f41a60c11a63f26a7b3985c1f4ddba))

## [7.4.3](https://github.com/myparcelnl/sdk/compare/v7.4.2...v7.4.3) (2022-07-20)


### :bug: Bug Fixes

* allow null as pickup location country ([#426](https://github.com/myparcelnl/sdk/issues/426)) ([7af1e83](https://github.com/myparcelnl/sdk/commit/7af1e83081f28620064b38da13abaea5a632351d))

## [7.4.2](https://github.com/myparcelnl/sdk/compare/v7.4.1...v7.4.2) (2022-07-14)


### :bug: Bug Fixes

* support a maximum of 6 characters for the classification ([#425](https://github.com/myparcelnl/sdk/issues/425)) ([4bea27b](https://github.com/myparcelnl/sdk/commit/4bea27b36eb7d491095d5f11c99bb7be973b5c2d))

## [7.4.1](https://github.com/myparcelnl/sdk/compare/v7.4.0...v7.4.1) (2022-07-12)


### :bug: Bug Fixes

* import closure in helpers ([6e540b2](https://github.com/myparcelnl/sdk/commit/6e540b26b7cca5adb6de19aede67191a7c708914))

## [7.4.0](https://github.com/myparcelnl/sdk/compare/v7.3.1...v7.4.0) (2022-07-11)


### :sparkles: New Features

* add helper function checking empty values in array ([#423](https://github.com/myparcelnl/sdk/issues/423)) ([f0e6549](https://github.com/myparcelnl/sdk/commit/f0e65494daa40576745c42cdf301662365d781fc))

## [7.3.1](https://github.com/myparcelnl/sdk/compare/v7.3.0...v7.3.1) (2022-07-11)


### :bug: Bug Fixes

* consignment status given default value ([#424](https://github.com/myparcelnl/sdk/issues/424)) ([0df494e](https://github.com/myparcelnl/sdk/commit/0df494e358d3cf80b196e5a7d2ef687a7f7c705d))

## [7.3.0](https://github.com/myparcelnl/sdk/compare/v7.2.2...v7.3.0) (2022-06-16)


### :sparkles: New Features

* add order status webhook and add shipments to order ([#414](https://github.com/myparcelnl/sdk/issues/414)) ([ae89f92](https://github.com/myparcelnl/sdk/commit/ae89f92b4063b4f08da40b199c121732a38fce60))
* add weight validation to consignment ([#411](https://github.com/myparcelnl/sdk/issues/411)) ([d2cf46e](https://github.com/myparcelnl/sdk/commit/d2cf46eeb12d9d4d658ad61fd73e23d2041754f5))


### :bug: Bug Fixes

* adjust instabox shipment for ConsignmentAdapter usage ([#406](https://github.com/myparcelnl/sdk/issues/406)) ([ab0e6c7](https://github.com/myparcelnl/sdk/commit/ab0e6c756b5dd46958e476edc3619e138df56ac5))
* restore deprecated constant ([#416](https://github.com/myparcelnl/sdk/issues/416)) ([ffffa0d](https://github.com/myparcelnl/sdk/commit/ffffa0d2360809bf91f4bd9d2fb1669b9c37d71a))

### [7.2.2](https://github.com/myparcelnl/sdk/compare/v7.2.1...v7.2.2) (2022-04-15)


### :bug: Bug Fixes

* add drop-off point validation for orders ([#407](https://github.com/myparcelnl/sdk/issues/407)) ([0b6aa79](https://github.com/myparcelnl/sdk/commit/0b6aa7966d3f66d8de929c0ddebdda4c8f68a62f))

### [7.2.1](https://github.com/myparcelnl/sdk/compare/v7.2.0...v7.2.1) (2022-04-11)


### :bug: Bug Fixes

* **consignment:** allow omitting insurance field ([#409](https://github.com/myparcelnl/sdk/issues/409)) ([810ac4e](https://github.com/myparcelnl/sdk/commit/810ac4e46f82b8d8726a08d360b1f26fb7fad243))

## [7.2.0](https://github.com/myparcelnl/sdk/compare/v7.1.0...v7.2.0) (2022-04-11)


### :bug: Bug Fixes

* allow 6 characters for number suffix ([#399](https://github.com/myparcelnl/sdk/issues/399)) ([05f9ff3](https://github.com/myparcelnl/sdk/commit/05f9ff37dc578b81d03dfa9c6eb7339b143560f2))
* **consignment:** fix insurance from api not being set ([#396](https://github.com/myparcelnl/sdk/issues/396)) ([ffe8a87](https://github.com/myparcelnl/sdk/commit/ffe8a878ba77c63224bafe8b883a1cf163424c49))
* cut off label description at 45 characters ([ff9aae5](https://github.com/myparcelnl/sdk/commit/ff9aae53d7772b42f62319cffa9e1a7732632521))


### :sparkles: New Features

* account for pickup with shipment options ([#395](https://github.com/myparcelnl/sdk/issues/395)) ([3ce7ad9](https://github.com/myparcelnl/sdk/commit/3ce7ad9192383fa39b3e45deb25151b733e0ef43))
* add HasInstance trait ([#402](https://github.com/myparcelnl/sdk/issues/402)) ([6ee0506](https://github.com/myparcelnl/sdk/commit/6ee0506669db5031e0c3ab797e5645dfb302e4ba))
* allow omitting delivery date with pickup ([#401](https://github.com/myparcelnl/sdk/issues/401)) ([f1603f0](https://github.com/myparcelnl/sdk/commit/f1603f0f9fc0288b9c9d3cd9752d67f07b079995))
* set reference identifier for return shipments ([#394](https://github.com/myparcelnl/sdk/issues/394)) ([957ccc2](https://github.com/myparcelnl/sdk/commit/957ccc24a1b9aae9abea4b1c6af9401f1c9efb76))

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
