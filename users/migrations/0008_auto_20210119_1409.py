# Generated by Django 3.0.7 on 2021-01-19 12:09

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('users', '0007_auto_20210119_1404'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='studentprofile',
            name='school',
        ),
        migrations.AddField(
            model_name='userprofile',
            name='school',
            field=models.ManyToManyField(to='users.School'),
        ),
    ]
