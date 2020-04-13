    var data = {
        "address": {
            "cc": "NL",
            "postalCode": "2131 BC",
            "number": "679"
        },
        "txtWeekDays": [
            "Zondag",
            "Maandag",
            "Dinsdag",
            "Woensdag",
            "Donderdag",
            "Vrijdag",
            "Zaterdag"
        ],
        "translateENtoNL": {
            "monday": "maandag",
            "tuesday": "dindsag",
            "wednesday": "woensdag",
            "thursday": "donderdag",
            "friday": "vrijdag",
            "saturday": "zaterdag",
            "sunday": "zondag"
        },
        "config": {
            "apiBaseUrl": "https://api.myparcel.nl/",
            "deliveryTitle": "Bezorgen opp",
            "dropOffDays": "1;2;3;4;5;6",
            "cutoffTime": "17:00",
            "deliverydaysWindow": "5",
            "dropoffDelay": "0",
            "allowMondayDelivery": true,
            "saturdayCutoffTime": "14:30",
            "allowMorningDelivery": true,
            "deliveryMorningTitle": "Ochtendlevering",
            "priceMorningDelivery": "10",
            "deliveryStandardTitle": "",
            "priceStandardDelivery": "5.85",
            "allowEveningDelivery": true,
            "deliveryEveningTitle": "Avondlevering",
            "priceEveningDelivery": "1,25",
            "allowSignature": true,
            "signatureTitle": "Handtekening",
            "priceSignature": "0.36",
            "allowOnlyRecipient": true,
            "onlyRecipientTitle": "Alleen geadresseerde",
            "priceOnlyRecipient": "0.29",
            "allowPickupPoints": true,
            "pickupTitle": "Afhalen op locatie",
            "pricePickup": "5.85",
            "allowPickupExpress": true,
            "pricePickupExpress": "7,23"
        }
    };
    MyParcel.init(data);