import os
import pandas as pd
from django.core.management.base import BaseCommand
from plants.models import Plant

class Command(BaseCommand):
    help = 'Deletes existing plant data and imports from CSV file'

    def handle(self, *args, **options):
        # Step 1: Delete all existing plant data
        self.stdout.write(self.style.WARNING('Deleting all existing plant data...'))
        deleted_count, _ = Plant.objects.all().delete()
        self.stdout.write(self.style.SUCCESS(f'Deleted {deleted_count} plant records'))

        # Step 2: Import new data from CSV
        csv_path = 'plant_data.csv'
        
        if not os.path.exists(csv_path):
            self.stdout.write(self.style.ERROR('CSV file not found'))
            return

        try:
            # Try reading with UTF-8 first, fall back to latin-1 if that fails
            try:
                df = pd.read_csv(csv_path, encoding='utf-8')
            except UnicodeDecodeError:
                self.stdout.write(self.style.WARNING('UTF-8 failed, trying latin-1 encoding'))
                df = pd.read_csv(csv_path, encoding='latin-1')
            
            # Clean any problematic characters that might have slipped through
            df = df.applymap(lambda x: x.encode('latin-1', 'ignore').decode('latin-1') if isinstance(x, str) else x)
            
            created_count = 0
            for index, row in df.iterrows():
                try:
                    Plant.objects.create(
                        plant_name=row['plant_name'],
                        type=row['type'],
                        leaves=row['leaves'],
                        flowers=row['flowers'],
                        fruits=row['fruits'],
                        growth=row['growth'],
                        uses=row['uses'],
                        image_filename=row['image_filename']
                    )
                    created_count += 1
                except Exception as e:
                    self.stdout.write(self.style.WARNING(f"Error importing row {index + 1}: {str(e)}"))
                    continue
            
            self.stdout.write(
                self.style.SUCCESS(
                    f'Successfully imported {created_count} of {len(df)} plants'
                )
            )
            
        except pd.errors.EmptyDataError:
            self.stdout.write(self.style.ERROR('The CSV file is empty'))
        except Exception as e:
            self.stdout.write(self.style.ERROR(f'An unexpected error occurred: {str(e)}'))