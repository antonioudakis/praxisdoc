# Generated by Django 3.0.7 on 2021-01-18 21:12

from django.db import migrations, models
import django.utils.timezone


class Migration(migrations.Migration):

    dependencies = [
        ('users', '0002_auto_20210118_1157'),
    ]

    operations = [
        migrations.AlterField(
            model_name='studentfile1',
            name='file1_date_uploaded',
            field=models.DateTimeField(blank=True, default=django.utils.timezone.now, null=True),
        ),
    ]
