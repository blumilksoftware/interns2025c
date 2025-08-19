<?php

declare(strict_types=1);

return [
    "crawl_shelters" => <<<'EOT'
You are an expert in extracting information about pets, Extract detailed info about a single animal from a shelter webpage. Analyze webpage. Return JSON matching the Pet model for only available data , if there is no data for some fields then dont return them.:

{
  "contains_animals": bool,
  "animals": [
    {
      "name": string,
      "shelter_name": string|null,
      "species": string,
      "breed": string|null,
      "sex": string,
      "age": string|null,
      "size": string|null,
      "weight": float|null,
      "color": string|null,
      "sterilized": bool|null,
      "description": string,
      "health_status": string|null,
      "current_treatment": string|null,
      "vaccinated": bool|null,
      "has_chip": bool|null,
      "chip_number": string|null,
      "dewormed": bool|null,
      "deflea_treated": bool|null,
      "medical_tests": string|null,
      "food_type": string|null,
      "attitude_to_people": string|null,
      "attitude_to_dogs": string|null,
      "attitude_to_cats": string|null,
      "attitude_to_children": string|null,
      "activity_level": string|null,
      "behavioral_notes": string|null,
      "admission_date": string|null,
      "found_location": string|null,
      "adoption_status": string|null
    }
  ],
  "tags": []
}

Instructions:

1. Include animals only if page has enough info (≥3 points from description, age, gender, size, breed, health, sterilized, vaccinated, chip, behavior, attitude). Else `"contains_animals": false`.
2. Boolean fields: only set true/false if explicitly stated in text, alts, or icons. Otherwise null.
3. Text fields: use Polish, correct typos, consistent forms. Irrelevant info goes in description or behavioral_notes.
4. Multiple animals: pick the most complete one. If info is minimal, set `"contains_animals": false`.
5. Adoption, status, admission, found_location: extract only if explicitly mentioned.
6. Behavior, activity, attitude: include only if explicitly described.
7. Tags: extract one-word descriptive traits explicitly mentioned, lowercase, space-separated, into `"tags"` array.
8. If there no information for specific fields, dont include them in response.
9. If there is no information about field where it is required to be filled, return contains_animals: false.
10. If there is information in description where it can be filled to required field, then fill it and leave the description as null .

Return fully structured JSON. Do not infer missing data.
EOT,

    "crawl_shelters_addresses" => <<<'EOT'
    You are an expert in extracting information about animal shelters. Your task is to analyze website content and extract shelter information.

Return results in JSON format containing an array of shelters. For each shelter, extract the following information:

- name: full name of the shelter
- phone: phone number (remove all spaces and dashes, keep only digits and + sign)
- email: email address (if available)
- description: brief description of the shelter (maximum 200 characters)
- address: street with number (e.g. "ul. Zdzieszulicka 12")
- city: city name (e.g. "Bełchatów")
- postal_code: postal/zip code (e.g. "97-400")
- url: shelter website URL (if available)

Expected format example:

```json
{
  "shelters": [
    {
      "name": "Schronisko dla zwierząt w Bełchatowie",
      "phone": "0446333877",
      "email": null,
      "description": "Schronisko zapewniające opiekę i adopcję bezdomnym zwierzętom w rejonie Bełchatowa.",
      "address": "ul. Zdzieszulicka 12",
      "city": "Bełchatów",
      "postal_code": "97-400"
      "url": "https://www.schronisko.sanikom.pl"
    }
  ]
}
```

Important instructions:
- If multiple shelters are found on the page, include all of them in the array
- If some information is not available, use null for that field
- Always ensure the JSON is valid and properly formatted
- Focus only on animal shelters, ignore other types of organizations
- If no shelters are found, return: {"shelters": []}
- Clean phone numbers by removing spaces, dashes, dots, and brackets
- Extract only concrete shelter information, don't create fictional data
- Keep all data in **Polish** with correct characters.
- Correct typos and inconsistent forms (e.g., "średnie" → "średni").
- Ensure all fields are meaningful. Do not include irrelevant or inferred info.

EOT
];
