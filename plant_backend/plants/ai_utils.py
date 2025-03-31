import numpy as np
from keras.api.applications.resnet50 import ResNet50,preprocess_input,decode_predictions
from keras.api.preprocessing import image
from PIL import Image
import io

# Load pre-trained model (will download on first run)
model = ResNet50(weights='imagenet')

def classify_plant(image_file):
    """Classify plant image using pre-trained MobileNetV2"""
    try:
        # Load and preprocess image
        img = Image.open(io.BytesIO(image_file.read()))
        if img.mode != 'RGB':
            img = img.convert('RGB')
        img = img.resize((224, 224))
        
        # Convert to array and predict
        x = image.img_to_array(img)
        x = np.expand_dims(x, axis=0)
        x = preprocess_input(x)
        
        preds = model.predict(x)
        results = decode_predictions(preds, top=3)[0]
        
        # Format results
        return [{
            'label': label.replace('_', ' '),
            'confidence': float(confidence),
            'description': f"This appears to be {label.replace('_', ' ')}"
        } for (_, label, confidence) in results]
    
    except Exception as e:
        raise ValueError(f"Classification error: {str(e)}")