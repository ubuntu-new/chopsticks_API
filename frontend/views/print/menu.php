{
"attributes": [
{
"id": "thin",
"name": "Thin",
"price_impact": "0.0",
"selected_by_default": "true"
},
{
"id": "classic",
"name": "Classic",
"price_impact": "0.0",
"selected_by_default": "false"
},
{
"id": "pan",
"name": "Pan",
"price_impact": "0.0",
"selected_by_default": "false"
},
{
"id": "peperoni-small",
"name": "Extra Peperoni",
"price_impact": "1.0",
"selected_by_default": "false"
},
{
"id": "tomato-small",
"name": "Extra Tomato",
"price_impact": "2.0",
"selected_by_default": "false"
},
{
"id": "onion-small",
"name": "Extra Onion",
"price_impact": "1.0",
"selected_by_default": "false"
},
{
"id": "peperoni-medium",
"name": "Extra Peperoni",
"price_impact": "3.0",
"selected_by_default": "false"
},
{
"id": "tomato-medium",
"name": "Extra Tomato",
"price_impact": "5.0",
"selected_by_default": "false"
},
{
"id": "onion-medium",
"name": "Extra Onion",
"price_impact": "3.0",
"selected_by_default": "false"
},
{
"id": "peperoni-large",
"name": "Extra Peperoni",
"price_impact": "7.0",
"selected_by_default": "false"
},
{
"id": "tomato-large",
"name": "Extra Tomato",
"price_impact": "8.0",
"selected_by_default": "false"
},
{
"id": "onion-large",
"name": "Extra Onion",
"price_impact": "7.0",
"selected_by_default": "false"
},
{
"id": "cheese",
"name": "Cheese",
"price_impact": "0.0",
"selected_by_default": "false"
},
{
"id": "tomato",
"name": "Tomato",
"price_impact": "0.0",
"selected_by_default": "false"
},
{
"id": "margherita",
"name": "Margherita",
"price_impact": "0.0",
"selected_by_default": "false"
},
{
"id": "pepperoni",
"name": "Pepperoni",
"price_impact": "0.0",
"selected_by_default": "false"
}
],
"attribute_groups": [
{
"id": "base",
"name": "Choose the base",
"min": "1",
"max": "1",
"multiple_selection": "false",
"collapse": "false",
"attributes": [
"thin",
"classic",
"pan"
]
},
{
"id": "toppings-small",
"name": "Toppings to be added in Small Pizza",
"min": "0",
"max": "3",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"peperoni-small",
"tomato-small",
"onion-small"
]
},
{
"id": "toppings-medium",
"name": "Toppings to be added in Medium Pizza",
"min": "0",
"max": "3",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"peperoni-mediun",
"tomato-medium",
"onion-medium"
]
},
{
"id": "toppings-large",
"name": "Toppings to be added in Large Pizza",
"min": "0",
"max": "3",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"peperoni-large",
"tomato-large",
"onion-large"
]
},
{
"id": "toppings-deducted",
"name": "Toppings to be deducted",
"min": "0",
"max": "2",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"a-13",
"a-14"
]
},
{
"id": "first-half-base",
"name": "Choose the base for your first half",
"min": "1",
"max": "1",
"multiple_selection": "false",
"collapse": "false",
"attributes": [
"thin-small",
"classic-small",
"pan-small"
]
},
{
"id": "second-half-base",
"name": "Choose the base for your second half",
"min": "1",
"max": "1",
"multiple_selection": "false",
"collapse": "false",
"attributes": [
"thin-small",
"classic-small",
"pan-small"
]
},
{
"id": "toppings-first-small",
"name": "Toppings to be added in your first half Small Pizza",
"min": "0",
"max": "3",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"peperoni-small",
"tomato-small",
"onion-small"
]
},
{
"id": "toppings-second-small",
"name": "Toppings to be added in your second half Small Pizza",
"min": "0",
"max": "3",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"peperoni-small",
"tomato-small",
"onion-small"
]
},
{
"id": "toppings-deducted-first",
"name": "Toppings to be deducted in your first half Pizza",
"min": "0",
"max": "2",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"cheese",
"tomato"
]
},
{
"id": "toppings-deducted-second",
"name": "Toppings to be deducted in your second half Pizza",
"min": "0",
"max": "2",
"multiple_selection": "true",
"collapse": "true",
"attributes": [
"cheese",
"tomato"
]
},
{
"id": "first-half",
"name": "Your fisrt half pizza",
"min": "1",
"max": "1",
"multiple_selection": "false",
"collapse": "false",
"attributes": [
"margherita",
"pepperoni"
]
},
{
"id": "second-half",
"name": "Your second half pizza",
"min": "1",
"max": "1",
"multiple_selection": "false",
"collapse": "false",
"attributes": [
"margherita",
"pepperoni"
]
}
],
"products": [
{
"id": "id-1",
"name": "Pepperoni Small",
"price": "10.0",
"image_url": "https://fakeimg.pl/300",
"description": "Pepperoni pizza",
"attributes_groups": [
"base",
"toppings-small",
"toppings-deducted"
]
},
{
"id": "id-2",
"name": "Pepperoni Medium",
"price": "15.0",
"image_url": "https://fakeimg.pl/300",
"description": "Pepperoni pizza",
"attributes_groups": [
"base",
"toppings-medium",
"toppings-deducted"
]
},
{
"id": "id-3",
"name": "Pepperoni Large",
"price": "20.0",
"image_url": "https://fakeimg.pl/300",
"description": "Pepperoni pizza",
"attributes_groups": [
"base",
"toppings-large",
"toppings-deducted"
]
},
{
"id": "id-4",
"name": "Margherita Small",
"price": "11.0",
"image_url": "https://fakeimg.pl/300",
"description": "Amazing pizza margherita",
"attributes_groups": [
"base",
"toppings-small",
"toppings-deducted"
]
},
{
"id": "id-5",
"name": "Margherita Medium",
"price": "16.0",
"image_url": "https://fakeimg.pl/300",
"description": "Amazing pizza margherita",
"attributes_groups": [
"base",
"toppings-medium",
"toppings-deducted"
]
},
{
"id": "id-6",
"name": "Margherita Large",
"price": "21.0",
"image_url": "https://fakeimg.pl/300",
"description": "Amazing pizza margherita",
"attributes_groups": [
"base",
"toppings-large",
"toppings-deducted"
]
},
{
"id": "id-7",
"name": "Half/half pizza Small",
"price": "11.0",
"image_url": "https://fakeimg.pl/300",
"description": "Try two styles",
"attributes_groups": [
"first-half",
"second-half",
"first-half-base",
"second-half-base",
"toppings-first-small",
"toppings-second-small",
"toppings-deducted-first",
"toppings-deducted-second"
]
}
],
"collections": [
{
"name": "Pizzas",
"position": "1",
"sections": [
{
"name": "Pizzas",
"position": "1",
"products": [
"id-1",
"id-2"
]
}
]
}
]
}