from django.db import models

class Plant(models.Model):
    plant_name = models.CharField(max_length=255)
    type = models.CharField(max_length=255)
    leaves = models.TextField()
    flowers = models.TextField()
    fruits = models.TextField()
    growth = models.TextField()
    uses = models.TextField()
    image_filename = models.CharField(max_length=255)
    image = models.ImageField(upload_to='plants/', null=True, blank=True)

    def __str__(self):
        return self.plant_name