import openai
from django.conf import settings

def get_care_recommendations(plant_data):
    openai.api_key = settings.OPENAI_API_KEY
    
    prompt = f"""
    Based on the following plant information, provide detailed care recommendations:
    
    Name: {plant_data['plant_name']}
    Type: {plant_data['type']}
    Leaves: {plant_data['leaves']}
    Flowers: {plant_data['flowers']}
    Growth: {plant_data['growth']}
    
    Provide recommendations for:
    1. Watering frequency
    2. Sunlight requirements
    3. Soil type
    4. Fertilization
    5. Common issues to watch for
    """
    
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=[
            {"role": "system", "content": "You are a knowledgeable botanist providing plant care advice."},
            {"role": "user", "content": prompt}
        ],
        temperature=0.7,
        max_tokens=500
    )
    
    return response.choices[0].message.content