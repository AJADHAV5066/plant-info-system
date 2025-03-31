from django.urls import path
from .views import  PlantAIView, PlantList, PlantDetail, PlantSearch 

urlpatterns = [
    path('', PlantList.as_view()),
    path('<int:pk>/', PlantDetail.as_view()),
    path('search/', PlantSearch.as_view()),
    path('ai/identify/', PlantAIView.as_view(), name='plant-ai-identify'),
]