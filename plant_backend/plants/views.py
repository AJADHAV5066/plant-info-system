from rest_framework import generics
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.db.models import Q
from .models import Plant
from .serializers import PlantSerializer
from .ai_utils import classify_plant
import base64
from django.core.files.base import ContentFile

# Existing search and CRUD views
class PlantList(generics.ListCreateAPIView):
    """
    List all plants or create a new plant
    """
    queryset = Plant.objects.all()
    serializer_class = PlantSerializer

class PlantDetail(generics.RetrieveAPIView):
    """
    Retrieve a single plant instance
    """
    queryset = Plant.objects.all()
    serializer_class = PlantSerializer

class PlantSearch(generics.ListAPIView):
    """
    Search plants across multiple fields
    """
    serializer_class = PlantSerializer

    def get_queryset(self):
        query = self.request.query_params.get('q', '').strip()
        if not query:
            return Plant.objects.none()
            
        return Plant.objects.filter(
            Q(plant_name__icontains=query) |
            Q(type__icontains=query) |
            Q(leaves__icontains=query) |
            Q(flowers__icontains=query) |
            Q(fruits__icontains=query) |
            Q(growth__icontains=query) |
            Q(uses__icontains=query)
        ).distinct()

# New AI Feature Views




class PlantAIView(APIView):
    """
    Self-contained plant identification using pre-trained MobileNetV2
    Supports both file uploads and base64 encoded images
    """
    def post(self, request):
        # Check for image in either FILES or base64 in POST data
        image_file = None
        if 'image' in request.FILES:
            image_file = request.FILES['image']
        elif 'image' in request.data:
            # Handle base64 encoded image
            try:
                image_data = base64.b64decode(request.data['image'].split(',')[-1])
                image_file = ContentFile(
                    image_data, 
                    name=request.data.get('filename', 'plant.jpg')
                )
            except (KeyError, ValueError, TypeError) as e:
                return Response(
                    {'error': 'Invalid base64 image data'},
                    status=status.HTTP_400_BAD_REQUEST
                )
        else:
            return Response(
                {'error': 'No image provided (use either file upload or base64)'},
                status=status.HTTP_400_BAD_REQUEST
            )

        try:
            # Validate the image
            if image_file.size > 5 * 1024 * 1024:  # 5MB
                return Response(
                    {'error': 'Image too large (max 5MB)'}, 
                    status=status.HTTP_400_BAD_REQUEST
                )
            
            if not image_file.content_type.startswith('image/'):
                return Response(
                    {'error': 'Only image files allowed'}, 
                    status=status.HTTP_400_BAD_REQUEST
                )

            # Classify image
            results = classify_plant(image_file)
            
            # Match with database plants
            matched_plants = []
            if results and len(results) > 0:
                top_label = results[0]['label']
                matched_plants = Plant.objects.filter(
                    Q(plant_name__icontains=top_label) |
                    Q(type__icontains=top_label)
                )[:3].values('id', 'plant_name', 'type', 'image_filename', 'uses')
            
            return Response({
                'success': True,
                'predictions': results,
                'matches': matched_plants,
                'top_suggestion': results[0]['label'] if results else None
            })
            
        except ValueError as e:
            return Response(
                {'success': False, 'error': str(e)},
                status=status.HTTP_400_BAD_REQUEST
            )
        except Exception as e:
            return Response(
                {
                    'success': False,
                    'error': 'Internal server error',
                    'detail': str(e)
                },
                status=status.HTTP_500_INTERNAL_SERVER_ERROR
            )